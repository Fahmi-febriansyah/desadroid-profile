<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(): void
    {
        $articles = Article::getAllPublished();
        $categories = Article::getCategories();
        $featured = !empty($articles) ? $articles[0] : null;
        $rest = !empty($articles) ? array_slice($articles, 1) : [];

        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/artikel';

        $this->render('articles/index', [
            'pageTitle' => 'Blog & Artikel Teknologi — Desadroid IT Consultant Bogor',
            'metaDescription' => 'Kumpulan artikel, panduan pengembangan web modern, arsitektur software, dan insight teknologi digital dari konsultan IT Desadroid di Bogor.',
            'metaKeywords' => 'blog teknologi bogor, artikel web developer, tips website bisnis, it consultant bogor, desadroid blog, jasa web bogor',
            'metaImage' => 'src/img/DESADROID.jpg',
            'canonical' => $canonical,
            'articles' => $articles,
            'categories' => $categories,
            'featured' => $featured,
            'rest' => $rest
        ]);
    }

    public function detail(string $slug = ''): void
    {
        $slug = trim($slug);
        if (empty($slug)) {
            $this->renderNotFound();
            return;
        }

        $article = Article::getBySlug($slug);
        if (!$article) {
            $this->renderNotFound();
            return;
        }

        // Increment article view count
        Article::incrementViews($article['id']);

        $related = Article::getRelated($article['id'], 6);
        $allArticles = Article::getAllSlugs();

        $scheme = $this->request->getScheme();
        $host = $this->request->getHost();
        $baseDir = $this->request->getBaseDir();
        $canonical = $scheme . '://' . $host . $baseDir . '/artikel/' . rawurlencode($article['slug']);

        // Resolve hero image
        if (!empty($article['featured_image'])) {
            $imgPath = $article['featured_image'];
            if (strpos($imgPath, 'http') === 0) {
                $heroImg = $imgPath;
            } else {
                $imgPath = ltrim($imgPath, '/');
                $parts = explode('/', $imgPath);
                $encodedParts = array_map('rawurlencode', $parts);
                $heroImg = $scheme . '://' . $host . $baseDir . '/' . implode('/', $encodedParts);
            }
        } else {
            $heroImg = 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200&q=80';
        }

        $readingTime = max(1, round(str_word_count(strip_tags($article['content'] ?? '')) / 200));

        $this->render('articles/detail', [
            'pageTitle' => htmlspecialchars($article['title']) . ' — Desadroid',
            'metaDescription' => htmlspecialchars($article['excerpt'] ?? ''),
            'metaImage' => $heroImg,
            'heroImg' => $heroImg,
            'canonical' => $canonical,
            'ogType' => 'article',
            'article' => $article,
            'related' => $related,
            'allArticles' => $allArticles,
            'readingTime' => $readingTime
        ]);
    }

    private function renderNotFound(): void
    {
        $this->response->setStatusCode(404);
        $this->render('errors/404', [
            'pageTitle' => '404 — Halaman Tidak Ditemukan | Desadroid',
            'metaDescription' => 'Maaf, halaman artikel yang Anda cari tidak dapat ditemukan.',
            'metaRobots' => 'noindex, follow'
        ]);
    }
}
