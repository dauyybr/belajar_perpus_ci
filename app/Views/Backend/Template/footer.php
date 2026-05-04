<script src="/Assets/js/jquery-1.11.1.min.js"></script>
<script src="/Assets/js/bootstrap.min.js"></script>
<script src="/Assets/js/chart.min.js"></script>
<script src="/Assets/js/chart-data.js"></script>
<script src="/Assets/js/easypiechart.js"></script>
<script src="/Assets/js/easypiechart-data.js"></script>
<script src="/Assets/js/bootstrap-datepicker.js"></script>
<script src="/Assets/js/bootstrap-table.js"></script>
<script src="/Assets/js/sweetalert2.min.js"></script>

<?php if (session()->getFlashdata('success')): ?>
<script type="text/javascript">
    $(document).ready(function(){
        swal("Success!", "<?php echo session()->getFlashdata('success') ?>", "success");
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script type="text/javascript">
    $(document).ready(function(){
        swal("Sorry!", "<?php echo session()->getFlashdata('error') ?>", "error");
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
<script type="text/javascript">
    $(document).ready(function(){
        swal("Warning!", "<?php echo session()->getFlashdata('warning') ?>", "warning");
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
<script type="text/javascript">
    $(document).ready(function(){
        swal("Info!", "<?php echo session()->getFlashdata('info') ?>", "info");
    });
</script>
<?php endif; ?>
</body>
</html>