<?php

// ============================================
// 1. BASE CONTROLLER - app/Controllers/BaseController.php (UPDATE)
// ============================================

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form', 'session'];
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->session = \Config\Services::session();
    }

    protected function isLoggedIn()
    {
        return $this->session->get('user_id') !== null;
    }

    protected function getUserData()
    {
        return [
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'nama_lengkap' => $this->session->get('nama_lengkap'),
            'role' => $this->session->get('role')
        ];
    }

    protected function isAdmin()
    {
        return $this->session->get('role') === 'admin';
    }

    protected function redirectIfNotLoggedIn()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    protected function redirectIfNotAdmin()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }
}