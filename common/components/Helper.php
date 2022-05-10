<?php

namespace common\components;

use common\models\music\Music;
use Yii;
use yii\bootstrap\Html;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii2mod\ftp\FtpClient;

class Helper extends Component
{
    
    public function getGoogleMapLink()
    {
        return "http://maps.googleapis.com/maps/api/js?v=3.exp&key=".Yii::$app->params['googleApiKey']."&language=fa";
    }
    
    /**
     * Remove all directories in given path
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $path location path
     */
    public function removeDirectories($path) {
        $blacklist = array('.', '..');
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                $addressPath = FileHelper::normalizePath($path . DIRECTORY_SEPARATOR . $file);
                if (!in_array($file, $blacklist) && is_dir($addressPath)) {
                    FileHelper::removeDirectory($addressPath);
                }
            }
            closedir($handle);
        }
        
        return true;
    }
    
    public function removeFilesOfDirectory($path) {
        $files = glob($path); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                @unlink($file); // delete file
            }
        }
    }

    public function getGridFilterRangeTimeStamp($date) {
        $date = Yii::$app->dateTimeAction->convertNumbers($date, true);
        if (!empty($date)) {
            $date = explode('_', $date);
            if (count($date) == 2) {
                $createdAtFrom = explode('-', trim($date[0]));
                $createdAtTo = explode('-', trim($date[1]));
                if (count($createdAtFrom) == 3 && count($createdAtTo) == 3) {
                    if (Yii::$app->language == 'fa-IR')
                    {
                        $createdAtFrom = Yii::$app->dateTimeAction->toGregorian($createdAtFrom[0], $createdAtFrom[1], $createdAtFrom[2]);
                        $createdAtTo = Yii::$app->dateTimeAction->toGregorian($createdAtTo[0], $createdAtTo[1], $createdAtTo[2]);
                        
                        if (!$createdAtTo && !$createdAtFrom)
                        {
                            return false;
                        }
                        $createdAtFrom = implode('-', $createdAtFrom);
                        $createdAtTo = implode('-', $createdAtTo);
                    }
                    else
                    {
                        $createdAtFrom = "{$createdAtFrom[0]}-{$createdAtFrom[1]}-{$createdAtFrom[2]}";
                        $createdAtTo = "{$createdAtTo[0]}-{$createdAtTo[1]}-{$createdAtTo[2]}";
                    }
                    return [strtotime("{$createdAtFrom} 00:00:00"), strtotime("{$createdAtTo} 23:59:59")];
                }
            }
        }

        return false;
    }

    public function getPaginatePerPage($size = 20) {
        if ((int)$size > 100)
        {
            $size = 100;
        }
        return Yii::$app->getRequest()->get('per-page', $size);
    }
    
    public function getMongoId($id = null)
    {
        if ($id)
        {
            if ($id instanceof \MongoDB\BSON\ObjectID)
            {
                return $id;
            }
            return new \MongoDB\BSON\ObjectID($id);
        }
        
        return new \MongoDB\BSON\ObjectID();
    }
    
    public function validateMobile($mobile)
    {
        return preg_match("/^09(0[1-3]|1[0-9]|3[0-9]|2[0-1])-?[0-9]{3}-?[0-9]{4}$/", $mobile);
    }
    
    /**
     * Create button for delete for yii gridview widget
     * 
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     * 
     * @param string $url url for delete action
     * @param string $permission permission for checking the user have an access to delete ir not.
     * @param string $pjaxGridName Gridview name to update the gridview after deleting.
     * @param string $messageContainer HTML container ID for show the message
     * @return string generated link
     */
    public function createDeleteButton($url, $permission = '', $pjaxGridName = '', $messageContainer = '') {
        if ($permission != '' && !Yii::$app->getUser()->can($permission))
            return '<i class="fa fa-trash"></i>';

        return Html::a('<i class="fa fa-trash"></i>', '#', [
                    'title' => Yii::t('app', 'Delete'),
                    "onclick" => "return Navar.deleteGridButton('{$url}', '" . Yii::t('app', 'Are you sure you want to delete this item?') . "', '{$pjaxGridName}', '{$messageContainer}' );"
        ]);
    }

    /**
     * Create button for updating item for yii gridview widget
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $url url for delete action
     * @param string $permission permission for checking the user have an access to edit ir not
     * @param boolean $useFancy show the given url result in fancy or not
     * @param boolean $usePjax Using PJAX for editing or not
     * @return string generated link
     */
    public function createUpdateButton($url, $permission = '', $useFancy = false, $usePjax = false) {
        if ($permission != '' && !Yii::$app->getUser()->can($permission))
            return '<i class="fa fa-pencil"></i>';

        return Html::a('<i class="fa fa-pencil"></i>', $url, [
                    'title' => Yii::t('app', 'Update'),
                    'class' => $useFancy ? 'fancybox fancybox.ajax' : '',
                    'data-pjax' => $usePjax ? '1' : '0',
        ]);
    }
    
     /**
     * Create button for view item for yii gridview widget
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $url url for delete action
     * @param string $permission permission for checking the user have an access to edit ir not
     * @param boolean $useFancy show the given url result in fancy or not
     * @param boolean $usePjax Using PJAX for editing or not
     * @return string generated link
     */
    public function createViewButton($url, $permission = '', $useFancy = false, $usePjax = false) {
        if ($permission != '' && !Yii::$app->getUser()->can($permission))
            return '<i class="fa fa-eye"></i>';

        return Html::a('<i class="fa fa-eye"></i>', $url, [
                    'title' => Yii::t('app', 'View'),
                    'class' => $useFancy ? 'fancybox fancybox.ajax' : '',
                    'data-pjax' => $usePjax ? '1' : '0',
        ]);
    }

    public function createAlertButton($url, $permission = '', $pjaxGridName = '', $messageContainer = '') {
        if ($permission != '' && !Yii::$app->getUser()->can($permission))
            return '<i class="fa fa-bell"></i>';

        return Html::a('<i class="fa fa-bell"></i>', '#', [
            'title' => Yii::t('app', 'Alert'),
            "onclick" => "return Navar.deleteGridButton('{$url}', '" . Yii::t('app', 'Are you sure you want to alert this item?') . "', '{$pjaxGridName}', '{$messageContainer}' );"
        ]);
    }
    
    public function normalizeChar($text)
    {
        return str_replace('ي', 'ی', trim($text));
    }

    public function getTypeStatus($type){
        if ($type == Music::TYPE_INAVAR){
            return 'status';
        }elseif($type == Music::TYPE_MUSICPLUS){
            return 'status_fa';
        }elseif($type == Music::TYPE_APP){
            return 'status_app';
        }elseif($type == Music::TYPE_SITE){
            return 'status_site';
        }elseif($type == Music::TYPE_ZIZZ){
            return 'status_zizz';
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }
    }

    public function getTypeLike($type){
        if ($type == Music::TYPE_INAVAR){
            return 'like';
        }elseif($type == Music::TYPE_MUSICPLUS){
            return 'like_fa';
        }elseif($type == Music::TYPE_APP || $type == Music::TYPE_SITE || $type == Music::TYPE_ZIZZ){
            return 'like_app';
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }
    }

    public function getTypeView($type){
        if ($type == Music::TYPE_INAVAR){
            return 'view';
        }elseif($type == Music::TYPE_MUSICPLUS){
            return 'view_fa';
        }elseif($type == Music::TYPE_APP || $type == Music::TYPE_SITE || $type == Music::TYPE_ZIZZ){
            return 'view_app';
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }
    }

    public function getTypePlay($type){
        if ($type == Music::TYPE_INAVAR){
            return 'play';
        }elseif($type == Music::TYPE_MUSICPLUS){
            return 'play_fa';
        }elseif($type == Music::TYPE_APP || $type == Music::TYPE_SITE || $type == Music::TYPE_ZIZZ){
            return 'play_app';
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }
    }

    public function ftpLogin(){

        $ftp = new FtpClient();
        $ftp->connect(Yii::$app->params['ftpHost']);
        $ftp->login(Yii::$app->params['ftpUsername'], Yii::$app->params['ftpPassword']);
        $ftp->chdir('www/storage/media');

        return $ftp;
    }

    public function deleteDirectoryFiles($directory){

        $files = glob("{$directory}/*"); // get all file names
        foreach($files as $file){ // iterate files

            if(is_dir($file)) {
                $this->deleteDirectoryFiles($file);
            }elseif(is_file($file))
                unlink($file); // delete file
        }
    }

    public function deleteDirectoryFilesAndDirectory($directory){

        $files = glob("{$directory}/*"); // get all file names
        foreach($files as $file){ // iterate files

            if(is_dir($file)) {
                $this->deleteDirectoryFiles($file);
            }elseif(is_file($file))
                unlink($file); // delete file
        }

        rmdir($directory);

    }
}

