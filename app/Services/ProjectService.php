<?php

namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function createProject(array $data): ProjectResource
    {
        $project = $this->projectRepository->create($data);

        return new ProjectResource($project);
    }

    public function getAllProjects(int $perPage = 10)
    {
        $projects = $this->projectRepository->getAll($perPage);

        return $projects;
    }

    public function getProject(int $id): ProjectResource
    {
        $project = $this->projectRepository->findById($id);

        return new ProjectResource($project);
    }

    public function updateProject(int $id, array $data): ProjectResource
    {
        $project = $this->projectRepository->findById($id);
        $this->projectRepository->update($project, $data);

        return new ProjectResource($project->refresh());
    }

    public function deleteProject(int $id): void
    {
        $project = $this->projectRepository->findById($id);
        $this->projectRepository->delete($project);
    }
}
