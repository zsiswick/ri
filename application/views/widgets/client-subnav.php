<div class="row">
  <div class="columns small-12 text-center">
    <dl class="sub-nav">
      <dd class="<?php echo( ($this->uri->segment(2)=='invoices')? 'active' : '' ) ?>"><a href="<?php echo base_url(); ?>index.php/clients/invoices/<?php echo $this->uri->segment(3, 0); ?>">Invoices</a></dd>
      <dd class="<?php echo( ($this->uri->segment(2)=='quotes')? 'active' : '' ) ?>"><a href="<?php echo base_url(); ?>index.php/clients/quotes/<?php echo $this->uri->segment(3, 0); ?>">Quotes</a></dd>
      <dd class="<?php echo( ($this->uri->segment(2)=='projects')? 'active' : '' ) ?>" id="joyride-begin"><a href="<?php echo base_url(); ?>index.php/clients/projects/<?php echo $this->uri->segment(3, 0); ?>">Projects</a></dd>
      <dd class="<?php echo( ($this->uri->segment(2)=='edit')? 'active' : '' ) ?>"><a href="<?php echo base_url(); ?>index.php/clients/edit/<?php echo $this->uri->segment(3, 0); ?>">Edit</a></dd>
    </dl>
  </div>
</div>
