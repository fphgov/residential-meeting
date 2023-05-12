<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\Table(name="votes")
 */
class Vote implements VoteInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"list", "option", "stat", "detail", "full_detail", "vote_list"})
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Question", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"stat", "full_detail"})
     */
    private Question $question;

    /**
     * @ORM\Column(name="answer", type="boolean", nullable=true)
     *
     * @Groups({"stat", "full_detail"})
     */
    private ?bool $answer;

    /**
     * @ORM\Column(name="zip_code", type="string")
     *
     * @Groups({"stat", "full_detail"})
     */
    private string $zipCode;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): ?bool
    {
        return $this->answer;
    }

    public function setAnswer(?bool $answer = null): void
    {
        $this->answer = $answer;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }
}
