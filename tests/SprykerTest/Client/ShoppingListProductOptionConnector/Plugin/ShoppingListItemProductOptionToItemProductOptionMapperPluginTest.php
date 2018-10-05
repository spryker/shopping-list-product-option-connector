<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOptionConnector\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ShoppingListProductOptionConnector\Dependency\Client\ShoppingListProductOptionConnectorToCartClientInterface;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionToItemProductOptionMapperPlugin;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorDependencyProvider;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ShoppingListProductOptionConnector
 * @group Plugin
 * @group ShoppingListItemProductOptionToItemProductOptionMapperPluginTest
 * Add your own group annotations below this line
 */
class ShoppingListItemProductOptionToItemProductOptionMapperPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemNotInCart(): void
    {
        // Prepare
        $productTransfer = $this->tester->haveProduct();
        $productOptionGroupTransfer = $this->tester->createProductOptionGroupTransfer($productTransfer->getSku());
        $shoppingListItemTransfer = (new ShoppingListItemTransfer());
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionTransfer = (new ProductOptionTransfer())
                ->setGroupName($productOptionGroupTransfer->getName())
                ->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue())
                ->setValue($productOptionValueTransfer->getValue());

            $shoppingListItemTransfer->addProductOption($productOptionTransfer);
        }
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample');

        $container = new Container();
        $cartMock = $this->getCartMock();

        $cartMock->method('getQuote')
            ->will($this->returnValue(new QuoteTransfer()));
        $cartMock->method('findQuoteItem')
            ->will($this->returnValue(null));

        $container[ShoppingListProductOptionConnectorDependencyProvider::CLIENT_CART] = function (Container $container) use ($cartMock) {
            return $cartMock;
        };

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->getShoppingListItemProductOptionToItemProductOptionMapperPlugin($container);
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        foreach ($actualResult->getProductOptions() as $productOptionTransfer) {
            $this->assertContains(
                $productOptionTransfer->getValue(),
                array_map(function (ProductOptionValueTransfer $productOptionValueTransfer) {
                    return $productOptionValueTransfer->getValue();
                }, $productOptionGroupTransfer->getProductOptionValues()->getArrayCopy())
            );
        }
    }

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemInCart(): void
    {
        // Prepare
        $sku = 'sku_sample';
        $groupKey = 'sample_group_key';
        $productTransfer = $this->tester->haveProduct();
        $productOptionGroupTransfer = $this->tester->createProductOptionGroupTransfer($productTransfer->getSku());
        $itemTransfer = (new ItemTransfer())->setSku($sku);
        $itemTransferInCart = (new ItemTransfer())->setSku($sku)->setGroupKey($groupKey);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->setSku($sku);
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionTransfer = (new ProductOptionTransfer())
                ->setGroupName($productOptionGroupTransfer->getName())
                ->setValue($productOptionValueTransfer->getValue());

            $shoppingListItemTransfer->addProductOption($productOptionTransfer);
            $itemTransferInCart->addProductOption($productOptionTransfer);
        }

        $container = new Container();
        $cartMock = $this->getCartMock();

        $cartMock->method('getQuote')
            ->will($this->returnValue((new QuoteTransfer())->addItem($itemTransferInCart)));
        $cartMock->method('findQuoteItem')
            ->will($this->returnValue($itemTransferInCart));

        $container[ShoppingListProductOptionConnectorDependencyProvider::CLIENT_CART] = function (Container $container) use ($cartMock) {
            return $cartMock;
        };

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->getShoppingListItemProductOptionToItemProductOptionMapperPlugin($container);
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        $this->assertSame($actualResult->getGroupKey(), $groupKey);
        foreach ($actualResult->getProductOptions() as $productOptionTransfer) {
            $this->assertContains(
                $productOptionTransfer->getValue(),
                array_map(function (ProductOptionValueTransfer $productOptionValueTransfer) {
                    return $productOptionValueTransfer->getValue();
                }, $productOptionGroupTransfer->getProductOptionValues()->getArrayCopy())
            );
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ShoppingListProductOptionConnector\Dependency\Client\ShoppingListProductOptionConnectorToCartClientInterface
     */
    protected function getCartMock()
    {
        return $this->getMockBuilder(ShoppingListProductOptionConnectorToCartClientInterface::class)->setMethods([
            'getQuote',
            'findQuoteItem',
        ])->disableOriginalConstructor()->getMock();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionToItemProductOptionMapperPlugin
     */
    protected function getShoppingListItemProductOptionToItemProductOptionMapperPlugin(Container $container): ShoppingListItemProductOptionToItemProductOptionMapperPlugin
    {
        $shoppingListItemProductOptionClientFactory = new ShoppingListProductOptionConnectorFactory();
        $shoppingListItemProductOptionClientFactory->setContainer($container);

        $shoppingListItemProductOptionMapperPlugin = new ShoppingListItemProductOptionToItemProductOptionMapperPlugin();
        $shoppingListItemProductOptionMapperPlugin->setFactory($shoppingListItemProductOptionClientFactory);

        return $shoppingListItemProductOptionMapperPlugin;
    }
}
