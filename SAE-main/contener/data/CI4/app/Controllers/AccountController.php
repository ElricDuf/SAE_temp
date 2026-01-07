<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\FavoriteModel;
use App\Models\ConversationModel;
use App\Models\SubscriptionModel;
use App\Models\WalletModel;
use CodeIgniter\I18n\Time;


class AccountController extends BaseController
{
    private function requireLogin()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }
        return $userId;
    }

    public function index()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Stats simples
        $db = db_connect();

        $beatsTotal  = (int) $db->table('beats')->where('user_id', $userId)->countAllResults();
        $beatsActive = (int) $db->table('beats')->where(['user_id' => $userId, 'status' => 'active'])->countAllResults();
        $beatsSold   = (int) $db->table('beats')->where(['user_id' => $userId, 'status' => 'sold'])->countAllResults();

        $favoritesCount = (int) $db->table('favorites')->where('user_id', $userId)->countAllResults();

        $convModel = new ConversationModel();
        $conversationsCount = count($convModel->listForUser($userId));

        $walletModel = new WalletModel();
        $wallet = $walletModel->where('user_id', $userId)->first();

        $subModel = new SubscriptionModel();
        $subscription = $subModel->getAnyActive($userId);

        return view('account/index', [
            'title' => 'Mon compte',
            'user'  => $user,
            'stats' => [
                'beats_total'        => $beatsTotal,
                'beats_active'       => $beatsActive,
                'beats_sold'         => $beatsSold,
                'favorites_count'    => $favoritesCount,
                'conversations_count'=> $conversationsCount,
            ],
            'wallet' => $wallet,
            'subscription' => $subscription,
        ]);
    }

    public function profile()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        return view('account/profile', [
            'title' => 'Mon profil',
            'user'  => $user,
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ]);
    }

    public function updateProfile()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $genre = trim((string) $this->request->getPost('artist_genre'));

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to('/account')->with('error', 'Utilisateur introuvable.');
        }

        $data = [
            'artist_genre' => $genre !== '' ? $genre : null,
        ];

        // Avatar (optionnel)
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = (string) $file->getMimeType();
            if (!str_starts_with($mime, 'image/')) {
                return redirect()->to('/account/profile')->with('error', 'Avatar invalide (image requise).');
            }

            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/avatars', $newName);

            $data['avatar'] = 'writable/uploads/avatars/' . $newName;
        }

        $userModel->update($userId, $data);

        return redirect()->to('/account/profile')->with('success', 'Profil mis à jour.');
    }

    public function favorites()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        // FavoriteModel n’a pas de méthode "list", donc on fait une requête join propre ici
        $db = db_connect();
        $favorites = $db->table('favorites f')
            ->select('b.id, b.title, b.price, b.bpm, b.musical_key, b.status, b.buyer_id, f.created_at')
            ->join('beats b', 'b.id = f.beat_id')
            ->where('f.user_id', $userId)
            ->orderBy('f.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('account/favorites', [
            'title' => 'Mes favoris',
            'favorites' => $favorites,
        ]);
    }

    public function conversations()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $convModel = new ConversationModel();
        $conversations = $convModel->listForUser($userId);

        return view('account/conversations', [
            'title' => 'Mes conversations',
            'conversations' => $conversations,
            'userId' => $userId,
        ]);
    }

    
    public function beatsIndex()
    {
        return redirect()->to('/my/beats');
    }

    public function beatCreateForm()
    {
        return redirect()->to('/beats/create');
    }

    public function beatCreate()
    {
        return redirect()->to('/beats/create'); // le POST est géré par BeatController::create
    }

    public function beatEditForm($id)
    {
        return redirect()->to("/beats/{$id}/edit");
    }

    public function beatUpdate($id)
    {
        return redirect()->to("/beats/{$id}/edit");
    }

    public function beatDelete($id)
    {
        return redirect()->to("/beats/{$id}/delete");
    }

    public function wallet()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $walletModel = new WalletModel();
        $wallet = $walletModel->where('user_id', $userId)->first();

        return view('account/wallet', [
            'title' => 'Wallet',
            'wallet' => $wallet,
        ]);
    }

    public function subscription()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $subModel = new SubscriptionModel();
        $subscription = $subModel->getAnyActive($userId);


        return view('account/subscription', [
            'title' => 'Abonnement',
            'subscription' => $subscription,
        ]);
    }

    public function moderation()
    {
        $this->requireLogin();
        return view('account/moderation', [
            'title' => 'Modération',
        ]);
    }
    public function buySubscription()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $type = (string) $this->request->getPost('type');
        if ($type === '') {
            $type = 'premium';
        }

        // Simulé : on active un abonnement 30 jours
        $subModel = new SubscriptionModel();

        $now = Time::now();
        $end = $now->addDays(30);

        // Si déjà actif de ce type, on prolonge 
        $existing = $subModel->getActive($userId, $type);
        if ($existing) {
            $currentEnd = !empty($existing['ends_at']) ? Time::parse($existing['ends_at']) : $now;
            $newEnd = $currentEnd->isAfter($now) ? $currentEnd->addDays(30) : $end;

            $subModel->update((int)$existing['id'], [
                'ends_at' => $newEnd->toDateTimeString(),
            ]);

            return redirect()->to('/account/subscription')
                ->with('success', "Abonnement prolongé (+30 jours).");
        }

        // Sinon on crée un abonnement actif
        $subModel->insert([
            'user_id' => $userId,
            'type' => $type,
            'status' => 'active',
            // valeurs “MVP”
            'commission_percent' => 0,
            'buyer_discount_percent' => 0,
            'monthly_credit_cents' => 0,
            'started_at' => $now->toDateTimeString(),
            'ends_at' => $end->toDateTimeString(),
        ]);

        return redirect()->to('/account/subscription')
            ->with('success', "Abonnement activé (simulation, 30 jours).");
    }

}
