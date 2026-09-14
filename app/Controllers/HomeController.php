<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;
use App\Models\Article;
use App\Models\Service;

class HomeController extends Controller
{
    public function index(): void
    {
        $projects = Project::getFeatured(3);
        $articles = Article::getLatest(3);
        $services = Service::getFeatured(6);

        $form_flash = $_SESSION['contact_flash'] ?? null;
        unset($_SESSION['contact_flash']);

        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/';

        $this->render('home/index', [
            'pageTitle' => 'Desadroid — Konsultan IT & Transformasi Digital Terpercaya Bogor',
            'metaDescription' => 'Desadroid menawarkan layanan IT profesional termasuk jasa pembuatan website modern, aplikasi mobile, desain UI/UX, dan pengembangan sistem scalable di Bogor.',
            'metaKeywords' => 'it consultant bogor, jasa pembuatan web bogor, konsultan it bogor, web developer bogor, software house bogor, pembuatan website bogor',
            'metaImage' => 'src/img/DESADROID.jpg',
            'canonical' => $canonical,
            'projects' => $projects,
            'articles' => $articles,
            'services' => $services,
            'form_flash' => $form_flash
        ]);
    }
}
