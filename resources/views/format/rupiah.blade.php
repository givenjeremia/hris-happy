<script>
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? +rupiah : '');
    }

    function convertRupiah(angka){
        return angka.replace(/\./g, '');
    }

    function numberOnly(angka) {
        return angka.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    }
    
</script>
