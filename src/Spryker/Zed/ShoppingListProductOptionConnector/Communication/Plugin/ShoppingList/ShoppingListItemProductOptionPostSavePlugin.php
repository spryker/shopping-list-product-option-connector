<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemPostSavePluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList\ShoppingListItemProductOptionBulkPostSavePlugin} instead.
 *
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorConfig getConfig()
 */
class ShoppingListItemProductOptionPostSavePlugin extends AbstractPlugin implements ShoppingListItemPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves the new list of product options to the shopping list item in persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function execute(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFacade()
            ->saveShoppingListItemProductOptions($shoppingListItemTransfer);
    }
}
