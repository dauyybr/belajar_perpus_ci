<script src="<?= base_url('Assets/js/jquery-1.11.1.min.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/chart.min.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/chart-data.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/easypiechart.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/easypiechart-data.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/bootstrap-datepicker.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/bootstrap-table.js'); ?>"></script>
    <script src="<?= base_url('Assets/js/sweetalert2.min.js'); ?>"></script>

    <script>
        !function ($) {
            $(document).on("click","ul.nav li.parent > a > span.icon", function(){        
                $(this).find('em:first').toggleClass("glyphicon-minus");      
            }); 
            $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
        }(window.jQuery);

        $(window).on('resize', function () {
          if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
        })
    </script>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            swal("Sorry!", "<?= session()->getFlashdata('error') ?>", "error");
        </script>
    <?php endif; ?>

</body>
</html>