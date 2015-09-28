<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\user\clients;

use SebastianBergmann\Comparator\ExceptionComparatorTest;
use yii\authclient\BaseClient;
use yii\authclient\ClientInterface;
use yii\authclient\OAuth2;
use yii\base\Exception;

class KD extends  OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'http://www.kolesa-darom.ru/oauth/';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'http://www.kolesa-darom.ru/netcat/m_admin/auth/token.php';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'http://www.kolesa-darom.ru/netcat/m_admin/auth/index.php';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'User_ID',
        'Email',
        'Name',
        'FamilyName',
        'City',
        'FullName',
        'Phone',
        'Login',
        'Password',
        'datarozhdeniya',
        'Otchestvo'
    ];


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $response = $this->api('', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
        //var_dump($response);die;
        $attributes = $response;
	
        $accessToken = $this->getAccessToken();
        //var_dump($accessToken);die;
        if (is_object($accessToken)) {
            $accessTokenParams = $accessToken->getParams();
            unset($accessTokenParams['access_token']);
            unset($accessTokenParams['expires_in']);
            //var_dump($accessTokenParams,$attributes);die;
            $attributes = array_merge($accessTokenParams, $attributes);
        }

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['uids'] = $accessToken->getParam('user_id');
        $params['access_token'] = $accessToken->getToken();
        //var_dump($method, $url, $params, $headers);die;
        return $this->sendRequest($method, $url, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'kd';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Kolesa-Darom';
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'User_ID',
            'email' => 'Email',
            'username' => 'Login',
            ];
    }
}