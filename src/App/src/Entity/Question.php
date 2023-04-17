<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @ORM\Table(name="questions")
 */
class Question implements QuestionInterface
{
    use EntityActiveTrait;
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"list", "option", "detail", "full_detail", "vote_list"})
     */
    protected int $id;

    /**
     * @ORM\Column(name="question", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $question;

    /**
     * @ORM\Column(name="question_short", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $questionShort;

    /**
     * @ORM\Column(name="option_label_yes", type="string")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $optionLabelYes;

    /**
     * @ORM\Column(name="option_label_no", type="string")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $optionLabelNo;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $description;

    /**
     * @ORM\Column(name="description_option_yes", type="text")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $descriptionOptionYes;

    /**
     * @ORM\Column(name="description_option_no", type="text")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $descriptionOptionNo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestionShort(string $questionShort): void
    {
        $this->questionShort = $questionShort;
    }

    public function getQuestionShort(): string
    {
        return $this->questionShort;
    }

    public function setOptionLabelYes(string $optionLabelYes): void
    {
        $this->optionLabelYes = $optionLabelYes;
    }

    public function getoptionLabelYes(): string
    {
        return $this->optionLabelYes;
    }

    public function setOptionLabelNo(string $optionLabelNo): void
    {
        $this->optionLabelNo = $optionLabelNo;
    }

    public function getoptionLabelNo(): string
    {
        return $this->optionLabelNo;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescriptionOptionYes(string $descriptionOptionYes): void
    {
        $this->descriptionOptionYes = $descriptionOptionYes;
    }

    public function getDescriptionOptionYes(): string
    {
        return $this->descriptionOptionYes;
    }

    public function setDescriptionOptionNo(string $descriptionOptionNo): void
    {
        $this->descriptionOptionNo = $descriptionOptionNo;
    }

    public function getDescriptionOptionNo(): string
    {
        return $this->descriptionOptionNo;
    }
}
