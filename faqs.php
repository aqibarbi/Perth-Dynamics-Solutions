<?php
/**
 * Template Name: FAQ Page
 */

get_header();
?>

<main>

  <!-- Breadcrumb -->
  <div class="breadcrumb-bar">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
        <li aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
      </ol>
    </nav>
  </div>

  <section id="faqs">
    <div class="container">
      <div class="title">
        <h2>FAQS</h2>
        <a class="section-cta" href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Still have questions? Contact us →</a>
      </div>
      <div class="faqlist">
        <div class="faqlist-col">

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-truck"></i></span>Does the price include delivery?</h4>
            <div>
              <p>Delivery is not included in the price and is charged per km. Please provide your address &amp; our
                staff can give you a quote including delivery.</p>
            </div>
          </div>
          
          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-percent"></i></span>What is GST (Goods and Services Tax)?</h4>
            <div>
              <p>GST (Goods and Services Tax) is a 10% tax applied to most goods and services sold in Australia, including portable homes.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-route"></i></span>Is nationwide delivery available?</h4>
            <div>
              <p>Yes, we arrange delivery across Australia and can coordinate transport as part of your package.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-tools"></i></span>Do you install the homes?</h4>
            <div>
              <p>We do offer setup &amp; fit out at an additional cost, dependent on your location.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-clipboard-check"></i></span>Do the trailers need to be
              registered?</h4>
            <div>
              <p>No, it does not need to be registered, just compliant. The only requirement is that the trailer has a
                VIN number and a compliance plate.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-layer-group"></i></span>What is the foundation of your
              homes?</h4>
            <div>
              <p>Our homes can be placed on various foundations, depending on the size, configuration and location.
                The most common types of foundations are concrete slabs or piers/footings.</p>
            </div>
          </div>

        </div>
        <div class="faqlist-col">

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-clock"></i></span>What is the turnaround time?</h4>
            <div>
              <p>If the unit is not in stock or has been made to order, please allow a 10-12 week turnaround.
                However, if we have the item in stock we can arrange immediate delivery.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-shield-alt"></i></span>Do they come with warranty?</h4>
            <div>
              <p>Yes, our homes come with an industry leading 24-month warranty. Please see our Warranty Agreement
                for more information.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-solar-panel"></i></span>Can the units be fully off-grid?</h4>
            <div>
              <p>A variety of off-grid solutions are available. See our Accessories Page for details.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-certificate"></i></span>Are they built to Australian
              Standards?</h4>
            <div>
              <p>All electrical components are SAA/Global Mark certified and meet Australian Standards. Our plumbing
                components are fully WaterMark certified and compliant with Australian Standards, and our trailers
                are built to Australian Design Rules in accordance with VSB1.</p>
            </div>
          </div>

          <div class="item">
            <h4><span class="faq-icon-badge"><i class="fas fa-landmark"></i></span>Do I need council approval?</h4>
            <div>
              <p>Your relocatable building may qualify as a caravan, which can remove the need for council approval.
                However, each council applies the rules differently, so it's always best to check with your local
                authority.</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // FAQ accordion toggle
    document.querySelectorAll('#faqs .item h4').forEach(function (heading) {
      heading.addEventListener('click', function () {
        var isOpen = heading.classList.contains('on');

        // close all others (accordion behavior)
        document.querySelectorAll('#faqs .item h4').forEach(function (h) {
          h.classList.remove('on');
        });

        if (!isOpen) {
          heading.classList.add('on');
        }
      });
    });
  });
</script>

<?php get_footer(); ?>
