jQuery(document).ready(function($) {
    $('input[name="update_option"]').on('change', function() {
        if ($(this).val() === 'by_csv') {
            $('#csv-upload-section').show();
        } else {
            $('#csv-upload-section').hide();
        }

        // Show submit button once an option is selected
        $('#submit-button').show();
    });

    $('#alt-text-updater-form').on('submit', function(e) {
        e.preventDefault();

        var updateOption = $('input[name="update_option"]:checked').val();
        var offset = 0;

        // Show the progress bar and reset it
        $('#progress-bar-wrapper').show(); 
        $('#progress-bar').css('width', '0%');
        $('#progress-text').text('0%');
        $('#submit-button').prop('disabled', true);

        var processBatch = function(offset) {
            var formData = new FormData();
            formData.append('action', updateOption === 'by_title' ? 'wp_alt_text_updater_update_by_title' : 'wp_alt_text_updater_update_by_csv');
            formData.append('offset', offset);
            if (updateOption === 'by_csv') {
                formData.append('csv_file', $('#csv_file')[0].files[0]);
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Ensure progress is capped at 100%
                        var progress = Math.min(response.data.progress, 100);
                        $('#progress-bar').css('width', progress + '%');
                        $('#progress-text').text(progress + '%');

                        // Continue batch processing if there are more images
                        if (response.data.next_offset !== null) {
                            processBatch(response.data.next_offset); // Process next batch
                        } else {
                            $('#submit-button').prop('disabled', false); 
                            alert('Alt text update complete!');
                        }
                    } else {
                        alert('Error updating alt text.');
                        $('#submit-button').prop('disabled', false); 
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $('#submit-button').prop('disabled', false); 
                }
            });
        };

        processBatch(offset); 
    });
});
