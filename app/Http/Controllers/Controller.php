<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Suivi Académique",
 *      description="Documentation de l'API pour le suivi académique",
 *      @OA\Contact(
 *          email="contact@suivi-academique.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur API"
 * )
 *
 * @OA\Tag(
 *     name="Filières",
 *     description="Endpoints pour la gestion des filières"
 * )
 *
 * @OA\Tag(
 *     name="Niveaux",
 *     description="Endpoints pour la gestion des niveaux"
 * )
 *
 * @OA\Tag(
 *     name="UEs",
 *     description="Endpoints pour la gestion des UEs"
 * )
 *
 * @OA\Tag(
 *     name="ECs",
 *     description="Endpoints pour la gestion des ECs"
 * )
 *
 * @OA\Tag(
 *     name="Personnel",
 *     description="Endpoints pour la gestion du personnel"
 * )
 *
 *  @OA\Tag(
 *     name="Salles",
 *     description="Endpoints pour la gestion des salles"
 * )
 *
 *  @OA\Tag(
 *     name="Programmtion",
 *     description="Endpoints pour la gestion des prgrammations"
 * )
 */
abstract class Controller
{
    //
}
