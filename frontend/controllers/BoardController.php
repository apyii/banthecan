<?php

namespace frontend\controllers; //namespace must be the first statement

use yii;
use common\models\Board;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use common\models\User;
use yii\filters\AccessControl;


class BoardController extends \yii\web\Controller {

    const DEFAULT_PAGE_SIZE = 24;
    private $currentBoard = null;

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Initialize the Board to the Session Board_id, and implicitly
     * restrict all ticket queries to members of this board for
     * the actions: completed, backlog and index
     *
     * @param yii\base\Action $action
     * @return bool
     * @throws yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ($action->id == 'completed' or
            $action->id == 'backlog' or
            $action->id == 'index') {
            $this->currentBoard = Board::getActiveboard();
        }

        return true; // or false to not run the action
    }
    /**
     * Default Action, shows active tickets in a KanBan Board
     */
    public function actionIndex()
    {
        $this->layout = 'right';
        Yii::$app->getUser()->setReturnUrl('/board/index');

        return $this->render('index', [
            'board' => $this->currentBoard,
        ]);
    }

    /**
     * Shows tickets in the Backlog
     */
    public function actionBacklog() {

        $currentPageSize = Yii::$app->request->post('per-page', self::DEFAULT_PAGE_SIZE);

        $this->layout = 'left-right';
        $boardRecord = Board::getActiveboard();
        $searchModel = Yii::createObject('common\models\TicketSearch');

        Yii::$app->ticketDecorationManager
                 ->registerDecorations($boardRecord->ticket_backlog_configuration);

        $dataProvider = $searchModel->search(Yii::$app->request->post(), 0);
        $dataProvider->pagination->defaultPageSize = self::DEFAULT_PAGE_SIZE;
        $dataProvider->pagination->pageSizeLimit = [1, 500];
        $dataProvider->pagination->pageSize = $currentPageSize;
        $dataProvider->sort = $this->createSortObject();

        Yii::$app->getUser()->setReturnUrl('/board/backlog');


        return $this->render('backlog', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageTitle' => $this->currentBoard->backlog_name,
            'action' => $this->action->id,
            'currentPageSize' => $currentPageSize,
        ]);
    }

    /**
     * Shows completed tickets
     */
    public function actionCompleted() {
        $this->layout = 'left-right';
        $boardRecord = Board::getActiveboard();
        $searchModel = Yii::createObject('common\models\TicketSearch');

        Yii::$app->ticketDecorationManager
            ->registerDecorations($boardRecord->ticket_completed_configuration);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, -1);
        $dataProvider->pagination->defaultPageSize = self::DEFAULT_PAGE_SIZE;
        $dataProvider->pagination->pageSizeLimit = [1, 500];
        $dataProvider->sort = $this->createSortObject();

        Yii::$app->getUser()->setReturnUrl('/board/completed');

        return $this->render('completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageTitle' => $this->currentBoard->completed_name,
            'action' => $this->action->id,
        ]);
    }

    /**
     * Allows the current user to select the active board from his/her board options
     */
    public function actionSelect() {
        $userBoardId = explode(',', User::findOne(Yii::$app->getUser()->id)->board_id);

        $userBoards = new ActiveDataProvider([
            'query' => Board::find()->where(['id' => $userBoardId]),
        ]);
        $boardCount = $userBoards->getTotalCount();

        if ($boardCount == 0) {
            // No Boards, log user out
            Yii::$app->user->logout();
            return $this->render('noBoard');
        } elseif ($boardCount == 1) {
            // Only one board for user, activate it automatically
            $activeBoardId = $userBoards->getModels()[0]->id;
            $this->redirect(['activate','id' => $activeBoardId]);
        } else {
            // USer must select which board to activate
            return $this->render('select',['userBoards' => $userBoards]);
        }
    }

    /**
     * Activates the Board for the current User. This means the selected board is made
     * available globally via cookies and(or) sessions
     */
    public function actionActivate() {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $activeBoardId = $request->get('id');
        $session->set('currentBoardId' , $activeBoardId);
        $boardRecord = Board::getActiveboard();
        $session->setFlash('success', 'Board activated: ' . $boardRecord->title);
        Yii::$app->params['title'] = $boardRecord->title;
        if ($cookie = Yii::$app->response->cookies->get('_identity')) {
            $cookie->expire = time() + 86400 * 365;
        }
        $this->goHome();
    }

    /**
     * Creates the sort Object Needed for Backlog and Completed Listings
     * @return yii\data\Sort
     */
    protected function createSortObject()
    {
        $sort = new Sort([
            'attributes' => [
                'title',
                'created_at' => [
                    'label' => 'Created'
                ],
                /*'updated_at' => [
                    'label' => 'Updated'
                ],*/
            ],
            'defaultOrder' => [
                'created_at' => SORT_DESC,
                'title' => SORT_ASC,
            ]
        ]);

        return $sort;
    }
}
