<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorPersistenceFactory getFactory()
 */
class ShoppingListProductOptionConnectorEntityManager extends AbstractEntityManager implements ShoppingListProductOptionConnectorEntityManagerInterface
{
    /**
     * @param int $idShoppingListItem
     * @param int $idProductOption
     *
     * @return void
     */
    public function saveShoppingListItemProductOption(int $idShoppingListItem, int $idProductOption): void
    {
        $this->getFactory()
            ->createSpyShoppingListProductOption()
            ->setFkShoppingListItem($idShoppingListItem)
            ->setFkProductOptionValue($idProductOption)
            ->save();
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $shoppingListProductOptionEntities = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($shoppingListProductOptionEntities);
    }

    /**
     * @param int[] $idsProductOptionValue
     *
     * @return void
     */
    public function removeProductOptionValuesFromShoppingListItems(array $idsProductOptionValue): void
    {
        $shoppingListProductOptionEntities = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkProductOptionValue($idsProductOptionValue, Criteria::IN)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($shoppingListProductOptionEntities);
    }

    /**
     * @param \Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption[]|\Propel\Runtime\Collection\ObjectCollection $shoppingListProductOptionEntities
     *
     * @return void
     */
    protected function deleteEntitiesAndTriggerEvents(ObjectCollection $shoppingListProductOptionEntities): void
    {
        foreach ($shoppingListProductOptionEntities as $shoppingListProductOptionEntity) {
            $shoppingListProductOptionEntity->delete();
        }
    }
}
