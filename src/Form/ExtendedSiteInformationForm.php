<?php

namespace Drupal\node_json_data_extended\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm {

   /**
   * {@inheritdoc}
   */
      public function buildForm(array $form, FormStateInterface $form_state) {
        $site_config = $this->config('system.site');
        $form =  parent::buildForm($form, $form_state);
        $form['site_information']['apikey'] = [
            '#type' => 'textfield',
            '#title' => t('API Key'),
            '#default_value' => $site_config->get('apikey') ?: 'No API Key yet',
            '#description' => t("Custom field to set the API Key"),
        ];

        return $form;
    }

      public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('system.site')
          ->set('apikey', $form_state->getValue('apikey'))
          ->save();
        parent::submitForm($form, $form_state);
      }
}