<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Límites por plan (SaaS)
  |--------------------------------------------------------------------------
  */
  'limits' => [
      'free' => [
          'max_clients' => 3,
          'max_projects' => 5,
          'max_invoices_per_month' => 5,
          'max_client_portal_users' => 2,
          'pdf_export' => true,
          'csv_export' => false,
          'custom_branding' => false,
      ],
      'pro' => [
          'max_clients' => 100,
          'max_projects' => 200,
          'max_invoices_per_month' => 500,
          'max_client_portal_users' => 50,
          'pdf_export' => true,
          'csv_export' => true,
          'custom_branding' => true,
      ],
  ],

  'pricing' => [
      'free' => ['label' => 'Gratis', 'price_eur' => 0],
      'pro' => ['label' => 'Pro', 'price_eur' => 19, 'note' => 'Precio orientativo — activación manual por ahora'],
  ],

];
