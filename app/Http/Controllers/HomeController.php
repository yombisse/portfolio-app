<?php

namespace App\Http\Controllers;

use App\Services\PortfolioService;

class HomeController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService
    ) {}

    public function index()
    {
        $profile = $this->portfolioService->getProfile();
        $projects = $this->portfolioService->getPublishedProjects();
        $competences = $this->portfolioService->getCompetences();
        $experiences = $this->portfolioService->getExperiences();
        $formations = $this->portfolioService->getFormations();

        return view('home', compact('profile', 'projects', 'competences', 'experiences', 'formations'));
    }
}
