<?php 

declare(strict_types=1);

final class Project {
    private readonly int $project_id; //PK
    private string $name;

    private readonly int $owner_id; //FK to User

    private readonly DateTimeImmutable $created_at;

    public function __construct(int $project_id, string $name, int $owner_id, DateTimeImmutable $created_at){
        $this->project_id = $project_id;
        $this->name = $name;
        $this->owner_id = $owner_id;
        $this->created_at = $created_at;
    }

    // Getters
    public function getProjectId(): int {
        return $this->project_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->created_at;
    }

    public function getOwnerId(): int {
        return $this->owner_id;
    }

    //Setters
    public function setName(string $name): void {
        // No validation needed for name (Handled in services)
        $this->name = $name;
    }

}

?>
