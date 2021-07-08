<?php
namespace app\controllers;

use app\models\BooksSearch;
use app\models\Reserve;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\Books;
use app\models\Borrow;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class BooksController extends Controller
{
    public function behaviors()
    {
        return[
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['index']
                    ]
                ],
                /*'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],*/
            ]
        ];
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$model = $searchModel->search(Yii::$app->request->get());


        return $this->render('index',['model'=>$dataProvider, 'searchModel' => $searchModel]);
    }

    /**
     * Displays a single BooksCrud model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BooksCrud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Books();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BooksCrud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BooksCrud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index?action=deleted']);
    }

    /*
    lets user borrow a book -> creates a record in db
    */
    public function actionBorrow($id)
    {
        $user_id = Yii::$app->user->identity->id;

        $borrow = new Borrow();
        $borrow->user_id = $user_id;
        $borrow->book_id = $id;
        $borrow->save();

        $book = Books::findOne(['id' => $id]);
        $book->borrowed = 1;
        $book->save();

        return $this->redirect(['index?action=borrowed']);



    }

    /*
    lets user reserve a book -> creates a record in db
    */
    public function actionReserve($id)
    {
        $user_id = Yii::$app->user->identity->id;

        $reserve = new Reserve();
        $reserve->user_id = $user_id;
        $reserve->book_id = $id;
        $reserve->save();

        $book = Books::findOne(['id' => $id]);
        $book->reserved = 1;
        $book->save();

        return $this->redirect(['index?action=reserved']);



    }

    /*
    lets user return a borrowed book -> deletes a record in db
    */
    public function actionReturn($id)
    {

        $user_id = Yii::$app->user->identity->id;

        $borrow = Borrow::findOne(['book_id' => $id]);
        $borrow->delete();

        $book = Books::findOne(['id' => $id]);
        if ($book->reserved == 1) {
            $reservation = Reserve::findOne(['book_id' => $id]);
            $new_user_id = $reservation->user_id;
            $new_borrow = new Borrow();
            $new_borrow->user_id = $new_user_id;
            $new_borrow->book_id = $id;
            $new_borrow->save();

            $reservation->delete();

            $book->reserved = 0;
        } else {
            $book->borrowed = 0;
        }
        $book->save();

        return $this->redirect(Url::to('borrowed?action=returned&id='.$user_id));



    }

    /*
    lets user cancel a reservation -> deletes a record in db
    */
    public function actionCancelReservation($id)
    {

        $user_id = Yii::$app->user->identity->id;

        $borrow = Reserve::findOne(['book_id' => $id]);
        $borrow->delete();

        $book = Books::findOne(['id' => $id]);
        $book->reserved = 0;
        $book->save();

        return $this->redirect(Url::to('reserved?action=cancelled&id='.$user_id));

    }

    /*
    gets all books user has borrowed
    */
    public function actionBorrowed($id) {

        $model = new ActiveDataProvider([
            'query' => Books::find()
                ->leftJoin('library_borrows', '`library_books`.`id` = `library_borrows`.`book_id`')
                ->where(['user_id' => $id]),
            'pagination'=>[
                'pageSize'=>10
            ]
        ]);

        return $this->render('borrowed', ['model' => $model]);
    }

    /*
    gets all books user has reserved
    */
    public function actionReserved($id) {

        $model = new ActiveDataProvider([
            'query' => Books::find()
                ->leftJoin('library_reservations', '`library_books`.`id` = `library_reservations`.`book_id`')
                ->where(['user_id' => $id]),
            'pagination'=>[
                'pageSize'=>10
            ]
        ]);

        return $this->render('reserved', ['model' => $model]);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /*public function actionAdd()
    {
        $model = new Books();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->save();
            return $this->redirect(Url::to('add?action=added'));
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }*/

}