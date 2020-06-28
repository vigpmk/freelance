<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Your Company',
        'product' => 'Your Product',
        'street' => 'PO Box 111',
        'location' => 'Your Town, NY 12345',
        'phone' => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = null;

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'superadmin@test.com',
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = false;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {

      //Spark::useStripe()->noCardUpFront()->teamTrialDays(10);
      Spark::useRoles([
        'admin' => 'Admin',
        'project_manager' => 'Project Manager',
        'finance' => 'Finance Manager',
        'team_lead'=>'Team Lead',
        'qc'=>'Quality Control',
        'designer'=>'Designer'
      ]);

      Spark::freeTeamPlan()
          ->features([
              'First', 'Second', 'Third'
          ]);

      Spark::prefixTeamsAs('companies');
      Spark::noAdditionalTeams();


      /*Spark::useRoles([
          'owner' => 'Account Owner',
          'member' => 'Member',
      ]);*/

        //Spark::useStripe()->noCardUpFront()->trialDays(10);
        //Spark::noAdditionalTeams();
        //Spark::identifyTeamsByPath();

        /*Spark::freeTeamPlan()
            ->features([
                'First', 'Second', 'Third'
       ]);*/

        /*Spark::freePlan()
            ->features([
                'First', 'Second', 'Third'
            ]);*/

        /*Spark::plan('Basic', 'provider-id-1')
            ->price(10)
            ->features([
                'First', 'Second', 'Third'
            ]);*/
    }
}
