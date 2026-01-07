<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Models\MessageModel;

class MessageController extends BaseController
{
    /**
     * POST /conversations/{id}/message
     */
    public function send(int $conversationId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $content = trim((string) $this->request->getPost('content'));

        if ($content === '') {
            return redirect()->to('/conversations/' . $conversationId)
                ->with('error', 'Message vide.');
        }

        $convModel = new ConversationModel();
        if (!$convModel->isParticipant($conversationId, $userId)) {
            return redirect()->to('/conversations')->with('error', 'Accès refusé.');
        }

        $msgModel = new MessageModel();
        $msgModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $userId,
            'content'         => $content,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/conversations/' . $conversationId);
    }
}
