<?php

namespace App\Http\Controllers;

use App\Services\PortfolioService;

class ProjectController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService
    ) {}

    public function index()
    {
        $projects = $this->portfolioService->getProjects();

        return view('projects.index', compact('projects'));
    }

    public function show(string $id)
    {
        $project = $this->portfolioService->getPublishedProjects()
            ->firstWhere('id', $id);

        abort_if(! $project, 404);

        return view('projects.show', compact('project'));
    }
}
