<?php

namespace api\controllers;

use api\models\ResendVerificationEmailForm;
//use api\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use common\models\LoginForm;
use common\models\User;

use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;

use api\models\RatesRequest;
use api\models\ConvertRequest;

/**
 * Site controller
 */


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public  $enableCsrfValidation = false;

    public function behaviors()
    {
        return [

            /*'authenticator' => [
                'class' => HttpBearerAuth::class,                
            ],*/

            /*'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],*/

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /*public function beforeAction()
    {

    }*/

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        
        $request = Yii::$app->request;

        $headers = $request->headers;

        $token = $headers['Authorization'];

        $user = new User();
        if(!$user->findIdentityByAccessToken($token)){
            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'status' => 'error',
                    'code' => 403,
                    'message' => 'Invalid token',
                ],  
            ]);
        }

        if($request->isGet){  // GET request rates

            $method = $request->get('method');

            if ($method == 'rates') {
                $model = null;
                if($request->get('currency')){
                    $model = new RatesRequest($request->get('currency'));
                }else{
                    $model = new RatesRequest('all');
                }
            
                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => \yii\web\Response::FORMAT_JSON,
                    'data' => [
                        $model->DataResponse(),
                    ],
                ]);
            }

        }elseif ($request->isPost) { // POST request convert

            $method = $request->getBodyParam('method');

            if ($method == 'convert') {

                $model = new ConvertRequest($request->getBodyParam('currency_from'), $request->getBodyParam('currency_to'), $request->getBodyParam('value'));
                    return \Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => [
                            $model->DataResponse(),
                        ],

                    ]);

            }         

        }
        return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => \yii\web\Response::FORMAT_JSON,
                    'data' => [
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Invalid method',
                    ],  
                ]);

        }

}
