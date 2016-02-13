<?php
  // SHARED VIEWS FROM THE STANDARD VIEW INVOICE PAGE
  // AND THE CLIENT VIEW INVOICE PAGE
  $totalAmount = $item[0]['amount'];
  $subTotal = 0;
  $payment_amount = 0;
  $invoice_tax_1 = $item[0]['invoice_tax_1'];
  $invoice_tax_2 = $item[0]['invoice_tax_2'];
  $discount = $item[0]['discount'];
  $tax_1_amnt = 0;
  $tax_2_amnt = 0;
  $this->load->helper('currency_helper');
  $currency = currency_method($item[0]['currency']);
?>
<div class="invoice-create list_header clearfix">
  <div class="row">
    <div class="small-12 medium-2 columns qty">
      Qty
    </div>
    <div class="small-12 medium-6 columns description">
      Description
    </div>
    <div class="small-12 medium-2 columns price text-right">
      Price
    </div>
    <div class="small-12 medium-2 text-right columns totalSum">
      Total
    </div>
  </div>
</div>

<div class="tabbed list no-rules">
  <?php
      foreach ($item['items'] as $invoice_item):

      $number = $invoice_item['quantity'] * $invoice_item['unit_cost'];
      $subTotal = $subTotal + $number;

      // CALCULATE TAX
      if ( $invoice_item['tax'] == $invoice_tax_1 )
      {
        $tx = ( $invoice_item['tax'] / 100 );
        $tax_1_amnt += $number * $tx;
      }
        else if ( $invoice_item['tax'] == $invoice_tax_2 )
      {
        $tx = ( $invoice_item['tax'] / 100 );
        $tax_2_amnt += $number * $tx;
      }

    ?>

    <div class="row">
      <div class="small-12 medium-2 large-2 columns hide-for-small-only">
        <?php echo $invoice_item['quantity'].' '.$invoice_item['unit']; ?>
      </div>
      <div class="small-12 medium-6 columns small-only-text-center">
        <?php echo nl2br($invoice_item['description']); ?>
      </div>
      <div class="small-12 small-only-text-center medium-2 columns text-right hide-for-small-only">
        <?php echo $invoice_item['unit_cost']; ?>
      </div>
      <div class="small-12 columns small-only-text-center show-for-small-only">
        <?php echo $invoice_item['quantity'].' x '.$invoice_item['unit_cost']; ?>
      </div>
      <div class="small-12 small-only-text-center medium-2 columns text-right totalSum" data-totalsum="<?php echo number_format((float)$number, 2, '.', ','); ?>">
        <?php echo number_format((float)$number, 2, '.', ',');?>
      </div>
      <div class="small-12 columns"><hr /></div>
    </div>

  <?php endforeach ?>
</div>

<section id="invoiceTotals">
<div class="row">

  <div class="medium-5 medium-push-7 columns">

    <div class="row">
      <div class="small-6 columns medium-text-right">
        <h4>Subtotal</h4>
      </div>
      <div class="small-6 columns text-right">
        <h4>
          <span id="invoiceSubtotal">
            <span id="amtLeft"><?php echo(number_format((float)($subTotal), 2, '.', ',')); ?>
          </span>
        </h4>
      </div>

      <?php if( $tax_1_amnt > 0 ) { ?>

      <div class="small-6 columns medium-text-right">
        <h4>Tax (<?php echo($item[0]['invoice_tax_1']);?>%)</h4>
      </div>
      <div class="small-6 columns text-right">
        <h4><span id="taxOne"><?= number_format((float)($tax_1_amnt), 2, '.', ','); ?></span></h4>
      </div>

      <?php } ?>

      <?php if( $tax_2_amnt > 0 ) { ?>

      <div class="small-6 columns medium-text-right">
        <h4>Tax (<?php echo($item[0]['invoice_tax_2']);?>%)</h4>
      </div>
      <div class="small-6 columns text-right">
        <h4><span id="taxTwo"><?= number_format((float)($tax_2_amnt), 2, '.', ','); ?></span></h4>
      </div>

      <?php } ?>

      <?php if ( $item[0]['discount'] > 0 ): ?>
        <div class="small-6 columns medium-text-right">
          <h4>Discount</h4>
        </div>
        <div class="small-6 columns text-right">
          <h4>-<span id="discount"><?php echo(number_format((float)$item[0]['discount'], 2, '.', ','));?></span></h4>
        </div>
      <?php endif; ?>

      <div class="small-12 columns">
        <hr />
      </div>

      <div class="small-6 columns medium-text-right">
        <h3>Total Due</h3>
      </div>
      <div class="small-6 columns text-right">
        <h3><span class="currency"><?php echo($currency)?></span><span id="invoiceTotal"></span><?php echo number_format((float)($item[0]['amount']), 2, '.', ',');?></span></h3>
      </div>

      <?php if(!empty($item['payments'])) { ?>
      <div class="columns small-6 medium-text-right">
        <h4>Paid</h4>
      </div>
      <div class="columns small-6 text-right">
        <h4>
          <span class="currency"><?php echo($currency)?></span><span id="amtPaid"><?php
              foreach ($item['payments'] as $payment){
                $number = $payment['payment_amount'] ;
                $payment_amount = $payment_amount + $number;
              }
              echo number_format((float)$payment_amount, 2, '.', ',');?>
            </span>
        </h4>
      </div>

      <div class="columns small-6 medium-text-right">
        <h4>Left</h4>
      </div>
      <div class="columns small-6 text-right">
        <h4><span class="currency"><?php echo($currency)?></span><span id="amtLeft"><?php
          $amtLeft = max($totalAmount - $payment_amount,0);
          echo(number_format((float)($amtLeft), 2, '.', ','));
        ?></span></h4>
      </div>
      <?php } ?>
    </div>


  </div>
  <?php
    if (!empty($item['settings'][0]['notes'])) { ?>
  <div class="medium-7 medium-pull-5 columns terms">
    <hr class="show-for-small-only">
    <h3>Payment Terms</h3>
    <p><?php echo nl2br($item['settings'][0]['notes']); ?></p>

  </div>
  <?php  } ?>
</div>
</section>
