<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/genre")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/", name="genre_index", methods={"GET"})
     * @param GenreRepository $genreRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('admin/genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully added new genre.');

            return $this->redirectToRoute('genre_new');
        }

        return $this->render('admin/genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genre_show", methods={"GET"})
     * @param Genre $genre
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Genre $genre): Response
    {
        return $this->render('admin/genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="genre_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Genre $genre
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('genre_index');
        }

        return $this->render('admin/genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genre_delete", methods={"DELETE"})
     * @param Request $request
     * @param Genre $genre
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Genre $genre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('genre_index');
    }

    /**
     * @Route("musicians", name="genre_musicians", methods={"GET"})
     * @param Request $request
     * @param GenreRepository $genreRepository
     * @return Response
     */
    public function showGenreMusicians(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findOneMatching($request->get('genre'));

        return $this->render('genre/musicians.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/search", name="genre_search", methods={"GET","POST"})
     * @param Request $request
     * @param GenreRepository $genreRepository
     * @return JsonResponse
     */
    public function getGenresJson(Request $request, GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findAllMatching($request->get('genre'));

        return $this->json([
            'genres' => $genres ?? null,
        ], 200, []);
    }
}
