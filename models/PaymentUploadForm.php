<?php
namespace app\models;

use yii\base\Model;

class PaymentUploadForm extends Model
{
    public $file;
    
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => FALSE, 'mimeTypes' => 'text/csv']
        ];
    }
}
?>
