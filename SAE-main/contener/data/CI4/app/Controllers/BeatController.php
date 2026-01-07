<?php

namespace App\Controllers;

use App\Models\BeatModel;
use App\Models\BeatFileModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BeatController extends BaseController
{
    public function index()
    {
        $beatModel = new BeatModel();
        $catModel = new CategoryModel(); 

        $beats = $beatModel->getDefaultFeed();

        return view('beats/index', [
            'title'      => 'Boutique',
            'beats'      => $beats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(), 
            'filters'    => [],   
            'doSearch'   => false, 
        ]);
    }

    public function search()
    {
        $filters = [
            'q'           => $this->request->getGet('q'),
            'category_id' => $this->request->getGet('category_id'),
            'bpm_min'     => $this->request->getGet('bpm_min'),
            'bpm_max'     => $this->request->getGet('bpm_max'),
            'price_min'   => $this->request->getGet('price_min'),
            'price_max'   => $this->request->getGet('price_max'),
            'musical_key' => $this->request->getGet('musical_key'),
            'do_search'   => 1,
        ];

        $beatModel = new BeatModel();
        $catModel = new CategoryModel(); 

        $beats = $beatModel->search($filters);

        return view('beats/index', [
            'title'      => 'Recherche',
            'beats'      => $beats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(), 
            'filters'    => $filters, 
            'doSearch'   => true,
        ]);
    }

    public function show(int $id)
    {
        $beatModel = new BeatModel();
        $beat = $beatModel->getOneWithJoins($id);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        $fileModel = new BeatFileModel();
        $previewPath = $fileModel->getPreviewPath($id);

        return view('beats/show', [
            'title' => $beat['title'],
            'beat' => $beat,
            'previewPath' => $previewPath,
        ]);
    }

    public function createForm()
    {
        if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 64 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Fichier trop volumineux.');
        }

        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $catModel = new CategoryModel();

        return view('beats/form', [
            'title'      => 'Publier un beat',
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'beat'       => null,
        ]);
    }

    public function create()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $title = trim((string) ($this->request->getPost('title') ?? ''));
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Titre obligatoire.');
        }

        $data = [
            'user_id'     => $userId,
            'category_id' => (int)($this->request->getPost('category_id') ?? 0) ?: null,
            'bpm'         => $this->request->getPost('bpm') !== null ? (int)$this->request->getPost('bpm') : null,
            'musical_key' => trim((string)($this->request->getPost('musical_key') ?? '')) ?: null,
            'tags'        => trim((string)($this->request->getPost('tags') ?? '')) ?: null,
            'title'       => $title,
            'description' => trim((string)($this->request->getPost('description') ?? '')) ?: null,
            'price'       => (float)($this->request->getPost('price') ?? 0),
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $beatModel = new BeatModel();
        $beatId = (int) $beatModel->insert($data, true);

        $fileModel = new BeatFileModel();

        // 1) preview mp3 -> public/uploads/previews/{beatId}
        $previewInfo = $this->saveUploadToPublic(
            $beatId,
            'preview_file',
            'uploads/previews',
            ['audio/mpeg', 'audio/mp3'],
            10 * 1024 * 1024
        );

        if ($previewInfo) {
            $fileModel->upsertFile(
                $beatId,
                'preview_mp3',
                $previewInfo['relativePath'],
                $previewInfo['mime'],
                $previewInfo['sizeBytes'],
                $previewInfo['sha256']
            );
        }

        // 2) master wav -> writable/uploads/masters/{beatId}
        $masterInfo = $this->saveUploadToWritable(
            $beatId,
            'original_file',             
            'uploads/masters',
            ['audio/wav', 'audio/x-wav'],
            50 * 1024 * 1024
        );

        if ($masterInfo) {
            $fileModel->upsertFile(
                $beatId,
                'master_wav',
                $masterInfo['relativePath'],
                $masterInfo['mime'],
                $masterInfo['sizeBytes'],
                $masterInfo['sha256']
            );
        }

        // wav + mp3 :
        if (!$previewInfo || !$masterInfo) {
            return redirect()->to('/my/beats')->with(
                'error',
                'Beat créé mais fichiers incomplets : il faut un MP3 (preview) + un WAV (master).'
            );
        }

        return redirect()->to('/my/beats')->with('success', 'Beat publié.');
    }

    public function myBeats()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beats = $beatModel->findBySeller($userId);

        return view('beats/my', [
            'title' => 'Mes beats',
            'beats' => $beats,
        ]);
    }

    /**
     * Download du WAV uniquement si l’utilisateur est l’acheteur.
     * URL : /beats/{id}/download
     */
    public function download(int $id)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beat = $beatModel->find($id);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        // Vérif achat
        if ((int)($beat['buyer_id'] ?? 0) !== $userId) {
            return redirect()->to('/beats/' . $id)->with('error', "Téléchargement refusé : achat requis.");
        }

        $fileModel = new BeatFileModel();
        $rel = $fileModel->getMasterPath($id);

        if (!$rel) {
            return redirect()->to('/beats/' . $id)->with('error', "Fichier WAV introuvable.");
        }

        $abs = WRITEPATH . rtrim($rel, '/\\');
        if (!is_file($abs)) {
            return redirect()->to('/beats/' . $id)->with('error', "Fichier WAV manquant sur le serveur.");
        }

        // force download
        return $this->response->download($abs, null);
    }

    // -------------------
    // Helpers upload
    // -------------------

    private function saveUploadToPublic(
        int $beatId,
        string $inputName,
        string $baseDirRelativeToPublic,
        array $allowedMimes,
        int $maxBytes
    ): ?array {
        $file = $this->request->getFile($inputName);
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $size = (int) $file->getSize();
        if ($size <= 0 || $size > $maxBytes) {
            throw new \RuntimeException("Fichier trop gros (max " . (int)($maxBytes / 1024 / 1024) . "MB).");
        }

        $mime = (string) $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            throw new \RuntimeException("Type de fichier invalide ($mime).");
        }

        $targetDir = rtrim(FCPATH, '/\\') . '/' . trim($baseDirRelativeToPublic, '/\\') . '/' . $beatId;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        $relative = trim($baseDirRelativeToPublic, '/\\') . '/' . $beatId . '/' . $newName;
        $abs = $targetDir . '/' . $newName;

        return [
            'relativePath' => $relative,
            'mime' => $mime,
            'sizeBytes' => $size,
            'sha256' => hash_file('sha256', $abs),
        ];
    }

    private function saveUploadToWritable(
        int $beatId,
        string $inputName,
        string $baseDirRelativeToWritable,
        array $allowedMimes,
        int $maxBytes
    ): ?array {
        $file = $this->request->getFile($inputName);
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $size = (int) $file->getSize();
        if ($size <= 0 || $size > $maxBytes) {
            throw new \RuntimeException("Fichier trop gros (max " . (int)($maxBytes / 1024 / 1024) . "MB).");
        }

        $mime = (string) $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            throw new \RuntimeException("Type de fichier invalide ($mime).");
        }

        $targetDir = rtrim(WRITEPATH, '/\\') . '/' . trim($baseDirRelativeToWritable, '/\\') . '/' . $beatId;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        $relative = trim($baseDirRelativeToWritable, '/\\') . '/' . $beatId . '/' . $newName;
        $abs = $targetDir . '/' . $newName;

        return [
            'relativePath' => $relative,
            'mime' => $mime,
            'sizeBytes' => $size,
            'sha256' => hash_file('sha256', $abs),
        ];
    }
}
