<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

enum Status : string {
    case APPROVED = "approved";
    case NEED_APPROVAL = "need_approval";
    case SPAM = 'spam';
}

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'text')]
    private ?string $content;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $username;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $votes = 0;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private ?Status $status;

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Answer
    {
        $this->status = $status;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    public function getVotesString(): string
    {
        return ($this->getVotes() > 0) ? '+ ' . abs($this->getVotes()) : '- ' . abs($this->getVotes());
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function getQuestionText(): string
    {
        return $this->question->getQuestion();
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function isApproved() : bool
    {
        return $this->getStatus() === Status::APPROVED;
    }
}
