<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostInterface;
use App\Entity\PostStatus;
use App\Entity\UserInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\Diactoros\UploadedFile;
use Laminas\Log\Logger;

use function basename;

final class PostService implements PostServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $postRepository;

    /** @var EntityRepository */
    private $postCategoryRepository;

    /** @var EntityRepository */
    private $postStatusRepository;

    /** @var Logger */
    private $audit;

    public function __construct(
        EntityManagerInterface $em,
        Logger $audit
    ) {
        $this->em                     = $em;
        $this->postRepository         = $this->em->getRepository(Post::class);
        $this->postCategoryRepository = $this->em->getRepository(PostCategory::class);
        $this->postStatusRepository   = $this->em->getRepository(PostStatus::class);
        $this->audit                  = $audit;
    }

    public function addPost(
        UserInterface $submitter,
        array $filteredParams
    ): void {
        $date = new DateTime();

        $post = new Post();

        $post->setTitle($filteredParams['title']);
        $post->setSlug($filteredParams['slug']);
        $post->setDescription($filteredParams['description']);
        $post->setContent($filteredParams['content']);
        $post->setAuthor($submitter);

        $category = $this->postCategoryRepository->findOneBy([
            'code' => $filteredParams['category'],
        ]);
        $status   = $this->postStatusRepository->findOneBy([
            'code' => $filteredParams['status'],
        ]);

        $post->setCategory($category);
        $post->setStatus($status);

        if ($filteredParams['file'] instanceof UploadedFile) {
            $this->addAttachment($post, $filteredParams['file'], $date);
        }

        $post->setCreatedAt($date);
        $post->setUpdatedAt($date);

        $this->em->persist($post);
        $this->em->flush();
    }

    public function modifyPost(
        PostInterface $post,
        array $filteredParams
    ): void {
        $date = new DateTime();

        $post->setTitle($filteredParams['title']);
        $post->setSlug($filteredParams['slug']);
        $post->setDescription($filteredParams['description']);
        $post->setContent($filteredParams['content']);

        $category = $this->postCategoryRepository->findOneBy([
            'code' => $filteredParams['category'],
        ]);

        $post->setCategory($category);

        $status = $this->postStatusRepository->findOneBy([
            'code' => $filteredParams['status'],
        ]);

        $post->setStatus($status);

        if ($filteredParams['file'] instanceof UploadedFile) {
            $this->addAttachment($post, $filteredParams['file'], $date);
        }

        $post->setCreatedAt(new DateTime($filteredParams['created']));
        $post->setUpdatedAt($date);

        $this->em->flush();
    }

    public function deletePost(
        UserInterface $submitter,
        PostInterface $post
    ): void {
        $postId = $post->getId();

        try {
            $this->em->remove($post);

            $this->em->flush();

            $this->audit->err('Success deleted post', [
                'extra' => 'Post ID: ' . $postId . ' deleted by ' . $submitter->getId(),
            ]);
        } catch (Exception $e) {
            $this->audit->err('Failed delete post from database', [
                'extra' => $e->getMessage(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->postRepository;
    }

    private function addAttachment(PostInterface $post, UploadedFile $file, DateTime $date): void
    {
        $filename = basename($file->getStream()->getMetaData('uri'));

        $media = new Media();
        $media->setFilename($filename);
        $media->setType($file->getClientMediaType());
        $media->setCreatedAt($date);
        $media->setUpdatedAt($date);

        $this->em->persist($media);

        $post->setFeaturedImage($media);
    }
}
