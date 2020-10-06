M.local_sftp = {}

M.local_sftp.fix_settings = function() {
    var val = $('#id_s_local_sftp_time').val();
    $('[id*="id_s_local_sftp_time_value_"]').parent().parent().parent().hide();
    $('[id*="id_s_local_sftp_time_value_'+val+'"]').parent().parent().parent().show();
}

M.local_sftp.check_settings = function() {
    $(document).ready(function() {
        if ($('#id_s_local_sftp_time').length) {
            $('#id_s_local_sftp_time').change(function() {
                M.local_sftp.fix_settings();
            }).change();
        }
    });
};
