<?php

namespace app\actions;

use Yii;


class IcmsSettingsAction extends \pheme\settings\SettingsAction
{
    public function run()
    {
        $model = new $this->modelClass();
        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                foreach ($model->toArray() as $key => $value) {
                    Yii::$app->settings->set($key, $value, $model->formName(), 'string');
                }
                Yii::$app->getSession()->addFlash(
                    'success',
                    \pheme\settings\Module::t(
                        'settings',
                        'Successfully saved settings on {section}',
                        ['section' => $model->formName()]
                    )
                );
            }
        }

        foreach ($model->attributes() as $key) {
            $model->{$key} = Yii::$app->settings->get($key, $model->formName());
        }
        
        return $this->controller->render($this->viewName, ['model' => $model]);
    }
}
