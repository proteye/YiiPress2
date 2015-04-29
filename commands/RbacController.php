<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\modules\user\models\User;

class RbacController extends Controller
{
    public function actionIndex()
    {
        echo 'yii rbac/init' . PHP_EOL;
        echo 'yii rbac/assign' . PHP_EOL;
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // add "createPost" permission
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update a post';
        $auth->add($updatePost);

        // add "user" role
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);
        
        // add "author" role: create, update post
        $author = $auth->createRole('author');
        $author->description = 'Author';
        $auth->add($author);
        $auth->addChild($author, $createPost);
        $auth->addChild($author, $updatePost);

        // add "admin" role: the permissions of the "user", "author" roles
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $author);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        /*
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
        */
    }

    public function actionAssign()
    {
        $auth = Yii::$app->authManager;
        $username = $this->prompt('Username:', ['required' => true]);
        $role = $this->prompt('Role:', ['required' => true]);
        $user = User::find()->where(['username' => $username])->one();
        $role = $auth->getRole($role);
        $this->log($auth->assign($role, $user->id));
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}