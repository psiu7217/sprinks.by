<?php
class ModelExtensionWmModuleWmSeoPro extends Model {
  public function getPathByProduct($product_id) {
    return $this->wmlicense->getPathByProduct($product_id);
  }

  public function getPathByCategory($category_id) {
    return $this->wmlicense->getPathByCategory($category_id);
  }
}