<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface;

class ShoppingListProductOptionReader implements ShoppingListProductOptionReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface
     */
    protected $shoppingListProductOptionRepository;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository
     */
    public function __construct(
        ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade,
        ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->shoppingListProductOptionRepository = $shoppingListProductOptionRepository;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ProductOptionCollectionTransfer
    {
        $productOptionCriteriaTransfer = $this->getProductOptionCriteriaTransfer($shoppingListItemTransfer);

        return $this->productOptionFacade->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer
     */
    public function getShoppingListProductOptionCollectionByShoppingListItemCollection(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListProductOptionCollectionTransfer {
        $shoppingListItemIds = $this->getShoppingListItemIdsFromShoppingListItemCollection($shoppingListItemCollectionTransfer);

        return $this->shoppingListProductOptionRepository
            ->getShoppingListProductOptionCollectionByShoppingListItemIds($shoppingListItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return array<int>
     */
    protected function getShoppingListItemIdsFromShoppingListItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): array
    {
        $shoppingListItemIds = [];
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemIds[] = $shoppingListItemTransfer->getIdShoppingListItem();
        }

        return $shoppingListItemIds;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCriteriaTransfer
     */
    protected function getProductOptionCriteriaTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ProductOptionCriteriaTransfer
    {
        $productOptionCriteriaTransfer = new ProductOptionCriteriaTransfer();

        $shoppingListItemProductOptionIds = $this->shoppingListProductOptionRepository
            ->getShoppingListItemProductOptionIdsByIdShoppingListItem(
                $shoppingListItemTransfer->getIdShoppingListItem(),
            );

        $productOptionCriteriaTransfer->setProductOptionIds($shoppingListItemProductOptionIds)
            ->setProductConcreteSku($shoppingListItemTransfer->getSku())
            ->setProductOptionGroupIsActive(true);

        $productOptionCriteriaTransfer->setCurrencyIsoCode(
            $shoppingListItemTransfer->getCurrencyIsoCode(),
        );

        $productOptionCriteriaTransfer->setPriceMode(
            $shoppingListItemTransfer->getPriceMode(),
        );

        return $productOptionCriteriaTransfer;
    }
}
