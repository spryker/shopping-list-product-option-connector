<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorFactory getFactory()
 */
class ProductOptionQuoteItemToItemMapperPlugin extends AbstractPlugin implements QuoteItemToItemMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Merges the item to the item existing in cart if they have the same productOptions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function map(ItemTransfer $quoteItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createQuoteItemToItemMapper()
            ->map($quoteItemTransfer, $itemTransfer);
    }
}
