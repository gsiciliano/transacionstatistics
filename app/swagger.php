<?php
//Generic API description for documentation purposes. Each call's documentation is contained in the relevant controller.
/**
 *
 * @OA\Info(
 *     version="1.0.1",
 *     title="Transaction statistics",
 *     description="REST Api",
 *     @OA\Contact(
 *         name="Gianluca Siciliano",
 *         email="gianluca.siciliano.79@gmail.com"
 *     ),
 * )
 *
 */
/**
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     name="passport",
 *     securityScheme="passport",
 *     in="header",
 *     scheme={"https"},
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="https://localhost/oauth/token",
 *         scopes={}
 *     )
 *  )
 */
