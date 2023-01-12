jQuery(document).ready(function($) { 
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
    /****************Employer Registration*****************************/
        //$( '#acf-field_62172eeef7b59' ).prepend( $('<option></option>').val('0').html('Select Province').attr({ selected: 'selected', disabled: 'disabled'}) );
        var jobId = getUrlVars()["job_id"];
        $( '#acf-field_62172eeef7b59' ).change(function () { //Province
            var selected_province = ''; // Selected value
     
            $( '#acf-field_62172eeef7b59 option:selected' ).each(function() {
                //selected_province += $( this ).text();
                selected_province += $( this ).val();
            });
     
            $( '#acf-field_62172f84f7b5a' ).attr( 'disabled', 'disabled' ); //city
     
            // If default is not selected get areas for selected province
            if( selected_province != 'Select Province' ) {
                // Send AJAX request
                data = {
                    action: 'pa_add_city',
                    pa_nonce: pa_vars.pa_nonce,
                    province: selected_province,
                    jobId : jobId
                };
                // Get response and populate area select field
                $.post( pa_vars.ajaxurl, data, function(response) {
     
                    if( response.data ){
                        // Disable 'Select Area' field until country is selected
                        $( '#acf-field_62172f84f7b5a' ).html( $('<option></option>').val('0').html('Select City').attr({ selected: 'selected', disabled: 'disabled'}) );
     
                        // Add areas to select field options
                        $.each(response.data, function(val, text) {
                            $( '#acf-field_62172f84f7b5a' ).append( $('<option></option>').val(text).html(text) );
                            if(response.selected_city != ''){
                                $( '#acf-field_62172f84f7b5a option[value="'+response.selected_city+'"]' ).attr('selected','selected');
                                $( '#acf-field_62172f84f7b5a option[value="'+response.selected_city+'"]' ).prop('selected',true);
                            }
                        });
     
                        // Enable 'Select Area' field
                        $( '#acf-field_62172f84f7b5a' ).removeAttr( 'disabled' );
                    };
                });
            }
        }).change();

        $( '#acf-field_62172f84f7b5a' ).change(function () { //city 
            var selected_city = ''; // Selected value
     
            $( '#acf-field_62172f84f7b5a option:selected' ).each(function() {
                selected_city += $( this ).val();
            });
            if((selected_city == "Others - AB") || (selected_city == "Others - BC") || (selected_city == "Others - MB") || (selected_city == "Others - NB") || (selected_city == "Others - NL") || (selected_city == "Others - NT") || (selected_city == "Others - NS") || (selected_city == "Others - NU") || (selected_city == "Others - ON") || (selected_city == "Others - PE") || (selected_city == "Others - QC") || (selected_city == "Others - SK") || (selected_city == "Others - YT") ){
                $('.acf-field-6219b2562cbab').removeClass('hide');
            } else { 
                $('.acf-field-6219b2562cbab').addClass('hide');
            }
        }).change();    

    /*****************Job Seeker Registration*******************************/    

        //$( '#acf-field_621771684c1f5' ).prepend( $('<option></option>').val('0').html('Select Province').attr({ selected: 'selected', disabled: 'disabled'}) );
        var jobId = getUrlVars()["job_id"];
        $( '#acf-field_621771684c1f5' ).change(function () { //Province
            var selected_province = ''; // Selected value
     
            $( '#acf-field_621771684c1f5 option:selected' ).each(function() {
                //selected_province += $( this ).text();
                selected_province += $( this ).val();
            });
     
            $( '#acf-field_6217718f4c1f6' ).attr( 'disabled', 'disabled' ); //city
     
            // If default is not selected get areas for selected province
            if( selected_province != 'Select Province' ) {
                // Send AJAX request
                data = {
                    action: 'pa_add_city',
                    pa_nonce: pa_vars.pa_nonce,
                    province: selected_province,
                    jobId : jobId
                };
                // Get response and populate area select field
                $.post( pa_vars.ajaxurl, data, function(response) {
     
                    if( response.data ){
                        // Disable 'Select Area' field until country is selected
                        $( '#acf-field_6217718f4c1f6' ).html( $('<option></option>').val('0').html('Select City').attr({ selected: 'selected', disabled: 'disabled'}) );
     
                        // Add areas to select field options
                        $.each(response.data, function(val, text) {
                            $( '#acf-field_6217718f4c1f6' ).append( $('<option></option>').val(text).html(text) );
                            if(response.selected_city != ''){
                                $( '#acf-field_6217718f4c1f6 option[value="'+response.selected_city+'"]' ).attr('selected','selected');
                                $( '#acf-field_6217718f4c1f6 option[value="'+response.selected_city+'"]' ).prop('selected',true);
                            }
                        });
     
                        // Enable 'Select Area' field
                        $( '#acf-field_6217718f4c1f6' ).removeAttr( 'disabled' );
                    };
                });
            }
        }).change();

        $( '#acf-field_6217718f4c1f6' ).change(function () { //city 
            var selected_city = ''; // Selected value
     
            $( '#acf-field_6217718f4c1f6 option:selected' ).each(function() {
                selected_city += $( this ).val();
            });
            if((selected_city == "Others - AB") || (selected_city == "Others - BC") || (selected_city == "Others - MB") || (selected_city == "Others - NB") || (selected_city == "Others - NL") || (selected_city == "Others - NT") || (selected_city == "Others - NS") || (selected_city == "Others - NU") || (selected_city == "Others - ON") || (selected_city == "Others - PE") || (selected_city == "Others - QC") || (selected_city == "Others - SK") || (selected_city == "Others - YT") ){
                $('.acf-field-6219b84add6f8').removeClass('hide');
            } else {
                $('.acf-field-6219b84add6f8').addClass('hide');
            }
        }).change(); 

    /*****************Add New Job*******************************/    

        //$( '#acf-field_621772fe669c6' ).prepend( $('<option></option>').val('0').html('Select Province').attr({ selected: 'selected', disabled: 'disabled'}) );
        var jobId = getUrlVars()["job_id"];
        $( '#acf-field_621772fe669c6' ).change(function () { //Province
            var selected_province = ''; // Selected value
     
            $( '#acf-field_621772fe669c6 option:selected' ).each(function() {
                //selected_province += $( this ).text();
                selected_province += $( this ).val();
            });
            
            $( '#acf-field_62177345669c7' ).attr( 'disabled', 'disabled' ); //city
     
            // If default is not selected get areas for selected province
            if( selected_province != 'Select Province' ) {
                // Send AJAX request
                data = {
                    action: 'pa_add_city',
                    pa_nonce: pa_vars.pa_nonce,
                    province: selected_province,
                    jobId : jobId
                };
                // Get response and populate area select field
                $.post( pa_vars.ajaxurl, data, function(response) {
                    
                    if( response.data ){
                        // Disable 'Select Area' field until country is selected
                        $( '#acf-field_62177345669c7' ).html( $('<option></option>').val('0').html('Select City').attr({ selected: 'selected', disabled: 'disabled'}) );
     
                        // Add areas to select field options
                        $.each(response.data, function(val, text) {
                            $( '#acf-field_62177345669c7' ).append( $('<option></option>').val(text).html(text) );
                            if(response.selected_city != ''){
                                $( '#acf-field_62177345669c7 option[value="'+response.selected_city+'"]' ).attr('selected','selected');
                                $( '#acf-field_62177345669c7 option[value="'+response.selected_city+'"]' ).prop('selected',true);
                            }
                        });

                        // Enable 'Select Area' field
                        $( '#acf-field_62177345669c7' ).removeAttr( 'disabled' );
                    };
                });
            }
        }).change();

        $( '#acf-field_62177345669c7' ).change(function () { //city 
            var selected_city = ''; // Selected value
     
            $( '#acf-field_62177345669c7 option:selected' ).each(function() {
                selected_city += $( this ).val();
            });
            if((selected_city == "Others - AB") || (selected_city == "Others - BC") || (selected_city == "Others - MB") || (selected_city == "Others - NB") || (selected_city == "Others - NL") || (selected_city == "Others - NT") || (selected_city == "Others - NS") || (selected_city == "Others - NU") || (selected_city == "Others - ON") || (selected_city == "Others - PE") || (selected_city == "Others - QC") || (selected_city == "Others - SK") || (selected_city == "Others - YT") ){
                $('.acf-field-6219c6458e04a').removeClass('hide');
            } else {
                $('.acf-field-6219c6458e04a').addClass('hide');
            }
        }).change();     


    /****************PDF Download************************/  
    $('#download_pdf').click(function (e) { 
        e.preventDefault();
        /*var pdf = new jsPDF('p', 'pt', 'letter')
        , fonts = [['DejaVu', 'normal'],['Times', 'Roman'], ['Helvetica', ''], ['Times', 'Italic']]
        , source = $('.openings-single')[0]
        , autoencode = true
        , specialElementHandlers = {
            '#bypassme': function(element, renderer){      
                return true
            }
        }

        margins = {
            top: 60,
            bottom: 60,
            left: 40,
            width: 522
          };

         
        pdf.fromHTML(
            source // HTML string or DOM elem ref.
            , margins.left // x coord
            , margins.top // y coord
            , {
                'width': margins.width // max width of content on PDF
                , 'elementHandlers': specialElementHandlers
            },
            function (dispose) {
              // dispose: object with X, Y of the last line add to the PDF
              //          this allow the insertion of new lines after html
                pdf.save('jobsportal'+$('#jobid').text()+'.pdf');
              },
            margins
        )*/
        
        //////*********************////////////

        /*const doc = new jsPDF({
            orientation: "landscape",
            unit: "in",
            format: [4, 2]
        });*/
        //var doc = new jsPDF();
        /*window.html2canvas = html2canvas;
        var elementHTML = $('#openings-single').html();
        var specialElementHandlers = {
            '#toPdf': function (element, renderer) {
                return true;
            }
        };
        doc.fromHTML(elementHTML, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });*/
         
        // Save the PDF
        //doc.text("Hello world!", 20, 20);
        /*doc.html(document.body, {
            callback: function (doc) {
             doc.save("test.pdf");
           },
           x: 10,
           y: 10
        });*/

        /*var specialElementHandlers = {
            '#getPDF': function(element, renderer){
              return true;
            },
            '.controls': function(element, renderer){
              return true;
            }
          };

        doc.fromHTML($('.openings-single').get(0), 15, 15, {
            'width': 170, 
            'elementHandlers': specialElementHandlers
          });  
        doc.save('Generated.pdf');*/


        /*html2pdf(document.getElementById('openings-single'), {
            margin: 10,
            filename: "my.pdf",
            image: {type: 'jpeg', quality: 1},
            html2canvas: {dpi: 72, letterRendering: true},
            jsPDF: {unit: 'mm', format: 'a4', orientation: 'landscape'},
            pdfCallback: pdfCallback
        })

        function pdfCallback(pdfObject) {
            console.log("pdfObject")
            console.log(pdfObject)
            var number_of_pages = pdfObject.internal.getNumberOfPages()
            var pdf_pages = pdfObject.internal.pages
            var myFooter = "Footer info"
            for (var i = 1; i < pdf_pages.length; i++) {
                // We are telling our pdfObject that we are now working on this page
                pdfObject.setPage(i)

                pdfObject.text("my header text", 10, 10)

                // The 10,200 value is only for A4 landscape. You need to define your own for other page sizes
                pdfObject.text(myFooter, 10, 200)
            }
        }*/

        var currentdate = new Date();
        var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() ;

        var jobid = document.getElementById('jobid').textContent;

        var element = document.getElementById('openings-single');

        var opt = {
            margin: [20, 10, 20, 10],
            filename: "jobsportal" + jobid + ".pdf",
            image: {type: 'jpeg', quality: 1},
            html2canvas: {dpi: 72, letterRendering: true,scale: 1,logging:true,scrollY: 0,useCORS: true},
            jsPDF: {  unit: 'mm', format: 'a4', orientation: 'portrait'},//portrait//landscape
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().from(element).set(opt).toPdf().get('pdf').then(function (pdf) {
          var totalPages = pdf.internal.getNumberOfPages();
          var pdf_pages = pdf.internal.pages;
          var title = 'JobsPortal.ca';
          for (i = 1; i <= totalPages; i++) {
            pdf.setPage(i);
            
            /*pdf.setFontSize(30);
            pdf.setTextColor(100);
            pdf.text(title, 10, 20)*/
            pdf.setFontSize(10);
            pdf.setTextColor(150);
            pdf.text(datetime, 10, 10)
            // pdf.text(datetime, pdf.internal.pageSize.getWidth()-30, 10)
            pdf.text(window.location.href, 10, pdf.internal.pageSize.getHeight()-10)
            pdf.text( i + '/' + totalPages, pdf.internal.pageSize.getWidth()-10, pdf.internal.pageSize.getHeight()-10)
            /*if (i > 1) {
                pdf.addPage(i); //8.5" x 11" in pts (in*72)
            } */
            
          } 
        }).save();
        
    });

    /****************PDF Download************************/  
    $('a#submit_link').click(function(e) {
        e.preventDefault();
      // wrap links, etc. here
      var html = $(".openings-single").html();
      console.log("html")
      console.log(html)
      $('#hidden_form_input').val(html);
      $('#hidden_form').submit();
    });
    


});

