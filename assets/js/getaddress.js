

(function ($) {
    "use strict";
    var openInstances = [];
    var globalInstance;
    var default_api_endpoint = "https://api.getAddress.io/v2/uk";
    var defaults = {
        
        api_key: "xls5lvnLS0qfYtSmY5yeMA7749",

        output_fields: {
            line_1: "#txt32",
            line_2: "#txt40",
	    post_town: "#txt39",	
            postcode: "#txt33"
        },

     

        api_endpoint:default_api_endpoint,

        // Input Postcode Field Configuration
        input: undefined,
        $input: undefined,
        input_label: "Enter Your Postcode",
        input_muted_style: "color:#CBCBCB;",
        input_class: "register-fields",
        input_id: "opc_input",

        // Button Lookup Configuration
        button: undefined,
        $button: undefined,
        button_id: "opc_button",
        button_label: "Find Your Address",
        button_class: "register-fields",
        button_disabled_message: "Fetching Addresses...",

        // Dropdown Address Configuration
        $dropdown: undefined,
        dropdown_id: "opc_dropdown",
        dropdown_select_message: "Select your Address",
        dropdown_class: "register-fields",

        // Error Message Configuration
        $error_message: undefined,
        error_message_id: "opc_error_message",
        error_message_postcode_invalid: "Please recheck your postcode, it seems to be incorrect",
        error_message_postcode_not_found: "Your postcode could not be found. Please type in your address",
        error_message_default: "Unable to find your address. Please contact info@propertypig.co.uk",
        error_message_class: "",

        // Prevent Unnecessary Lookups
        lookup_interval: 1000, // Disables lookup button in (ms) after click

        // Debug. Set to true to output API error messages to client
        debug_mode: false,

        // Register callbacks at specific stages
        onLookupSuccess: undefined,
        onLookupError: undefined,
        onAddressSelected: undefined,

        //sorts the dropdown
        sort_numerically: false
    };

      if($.support && $.support.cors === false){
                $.support.cors = true;
             }

    function Postcodes(options) {
        // Load the defaults
        this.config = {};
        $.extend(this, defaults);

        // Override with options
        if (options) {
            $.extend(this, options);
        }

        // Convert output_fields container to jQuery objects
        var $output_fields = {};
        for (var key in this.output_fields) {
            if (this.output_fields[key] !== undefined) {
                $output_fields[key] = $(this.output_fields[key]);
            }
        }
        this.$output_fields = $output_fields;
    }

    Array.prototype.clean = function (deleteValue) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {
                this.splice(i, 1);
                i--;
            }
        }
        return this;
    };

    if (!String.prototype.trim) {
        String.prototype.trim = function () {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    }

    Postcodes.prototype.setupPostcodeInput = function (context) {
        this.$context = context;
        this.setupInputField();
        this.setupLookupButton();
    };

    

    Postcodes.prototype.setupInputField = function () {
        var self = this;
        if ($(this.input).length) {
          
            this.$input = $(this.input).first();
        } else {
           
            this.$input = $('<input />', {
                type: "text",
                id: this.input_id,
                value: this.input_label
            })
            .appendTo(this.$context)
            .addClass(this.input_class)
            .val(this.input_label)
            .attr("style", this.input_muted_style)
            .attr("autocomplete", 'off')
            .submit(function () {
                return false;
            })
            .keypress(function (event) {
                if (event.which === 13) {
                    self.$button.trigger("click");
                }
            })
            .focus(function () {
                self.$input.removeAttr('style').val("");
            })
            .blur(function () {
                if (!self.$input.val()) {
                    self.$input.val(self.input_label);
                    self.$input.attr('style', self.input_muted_style);
                }
            });
        }
        return this.$input;
    };


    Postcodes.prototype.setupLookupButton = function () {
        var self = this;
        if ($(this.button).length) {
            this.$button = $(this.button).first();
        } else {
            this.$button = $('<button />', {
                html: this.button_label,
                id: this.button_id,
                type: "button"
            })
            .appendTo(this.$context)
            .addClass(this.button_class)
            .attr("onclick", "return false;")
            .submit(function () {
                return false;
            });
        }
        this.$button.click(function () {
            var postcode = self.$input.val();
            self.disableLookup();
            self.clearAll();
            self.lookupPostcode(postcode);
        });
        return this.$button;
    };

 
    Postcodes.prototype.disableLookup = function (message) {
        message = message || this.button_disabled_message;
        this.$button.prop('disabled', true).html(message);
    };


    Postcodes.prototype.enableLookup = function () {
        var self = this;
        if (self.lookup_interval === 0) {
            self.$button.prop('disabled', false).html(self.button_label);
        } else {
            setTimeout(function () {
                self.$button.prop('disabled', false).html(self.button_label);
            }, self.lookup_interval);
        }
    };

  
    Postcodes.prototype.clearAll = function () {
        this.setDropDown();
        this.setErrorMessage();
        this.setAddressFields();
    };

 
    Postcodes.prototype.removeAll = function () {
        this.$context = null;

        $.each([this.$input, this.$button, this.$dropdown, this.$error_message], function (index, element) {
            if (element) {
                element.remove();
            }
        });
    };


    function sortByDigits(array) {
                var re = /\D/g;
                array.sort(function(a, b) {
                                return(parseInt(a.replace(re, ""), 10) - parseInt(b.replace(re, ""), 10));
                });
                return(array);
    }


    Postcodes.prototype.lookupPostcode = function (postcode) {
        var self = this;


        if (!$.getAddress.validatePostcodeFormat(postcode)) {
            this.enableLookup();
            return self.setErrorMessage(this.error_message_postcode_invalid);
        }

        $.getAddress.lookupPostcode(postcode, self.api_key,
          // Successful
          function (data) {

              self.enableLookup();

              if (self.sort_numerically)
              {
                  data.Addresses = sortByDigits(data.Addresses);
              }

              self.setDropDown(data.Addresses, postcode);

              if (self.onLookupSuccess) {
                  self.onLookupSuccess(data);
              }
          },
          // Error
          function (xhr) {

              if (xhr.status == 404) {
                  self.setErrorMessage(self.error_message_postcode_not_found);
              } else {
                  self.setErrorMessage("Unable to connect to server");
              }

              self.enableLookup();
              if (self.onLookupError) {
                  self.onLookupError();
              }
          },
          self.api_endpoint
        );
    };

   
    Postcodes.prototype.setDropDown = function (data, postcode) {
        var self = this;

        // Remove Dropdown menu
        if (this.$dropdown && this.$dropdown.length) {
            this.$dropdown.remove();
            delete this.$dropdown;
        }

        // Return if undefined
        if (!data) {
            return ;
        }

        var dropDown = $('<select />', {
            id: self.dropdown_id
        }).
        addClass(self.dropdown_class);

        $('<option />', {
            value: "open",
            text: self.dropdown_select_message
        }).appendTo(dropDown);

        var length = data.length;


        for (var i = 0; i < length; i += 1) {
            var dataArray = data[i].split(',');
            var cleanDataArray = dataArray.clean(false);
            var text = cleanDataArray.join(',');

            $('<option />', {
                value: i,
                text: text
            }).appendTo(dropDown);
        }

        dropDown.appendTo(self.$context)
        .change(function () {
            var index = $(this).val();
            if (index >= 0) {

                self.setAddressFields(data[index], postcode);
                if (self.onAddressSelected) {
                    self.onAddressSelected.call(this, data[index]);
                }
            }
        });

        self.$dropdown = dropDown;

        return dropDown;
    };

   

    Postcodes.prototype.setErrorMessage = function (message) {
        if (this.$error_message && this.$error_message.length) {
            this.$error_message.remove();
            delete this.$error_message;
        }

        if (!message) {
            return;
        }

        this.$error_message = $('<p />', {
            html: message,
            id: this.error_message_id
        })
        .addClass(this.error_message_class)
        .appendTo(this.$context);

        return this.$error_message;
    };



    Postcodes.prototype.setAddressFields = function (data, postcode) {


        for (var key in this.$output_fields) {
            this.$output_fields[key].val("");
        }


        if (data) {
            var arry = data.split(',');

            if(this.$output_fields.line_1){
                this.$output_fields.line_1.val(arry[0].trim() || "");
            }

            if(this.$output_fields.line_2){
            this.$output_fields.line_2.val(arry[1].trim() || "");
            }


            if (arry[2].trim() && arry[3].trim() && this.$output_fields.line_3) {
                this.$output_fields.line_3.val(arry[2].trim() + ', ' + arry[3].trim());
            }
            else if (arry[2].trim() && this.$output_fields.line_3) {
                this.$output_fields.line_3.val(arry[2].trim() || "");
            }
            else if (arry[3].trim() &&  this.$output_fields.line_3) {
                this.$output_fields.line_3.val(arry[3].trim() || "");
            }


            if (arry[4].trim() && arry[5].trim() && this.$output_fields.post_town) {
                this.$output_fields.post_town.val(arry[4].trim() + ', ' + arry[5].trim());
            }
            else if (arry[5].trim() && this.$output_fields.post_town) {
                this.$output_fields.post_town.val(arry[5].trim() || "");
            }
            else if (arry[4].trim() && this.$output_fields.post_town) {
                this.$output_fields.post_town.val(arry[4].trim() || "");
            }

            if( this.$output_fields.county){
                this.$output_fields.county.val(arry[6].trim() || "");
             }

            if (postcode) {
                postcode = postcode.toUpperCase().trim();
            }
            if(this.$output_fields.postcode){
                this.$output_fields.postcode.val(postcode || "");
            }
        }
    };

    $.getAddress = {

        
        defaults: function () {
            return defaults;
        },

     
        setup: function (options) {
            globalInstance = new Postcodes(options);
            openInstances.push(globalInstance);
           
        },

        validatePostcodeFormat: function (postcode) {
            return !!postcode.match(/^[a-zA-Z0-9]{1,4}\s?\d[a-zA-Z]{2}$/);
        },

    
        lookupPostcode: function (postcode, api_key, success, error, endpoint) {

                var url = [endpoint, postcode].join('/');

                var data = {};

                if(endpoint === default_api_endpoint){
                    data = {
                        'api-key': api_key
                    };
                }

                var options = {
                    url: url,
                    data: data,
                    dataType: 'json',
                    timeout: 300000,
                    success: success
                };

            if (error) {
                options.error = error;
            }

            $.ajax(options);
        },

        clearAll: function () {
            var length = openInstances.length;
            for (var i = 0; i < length; i += 1) {
                openInstances[i].removeAll();
            }
        }

    };

   
    $.fn.getAddress = function (options) {
        if (options) {
           
            var postcodeLookup = new Postcodes(options);
            openInstances.push(postcodeLookup);
            postcodeLookup.setupPostcodeInput($(this));
        } else {
           
            globalInstance.setupPostcodeInput($(this));
        }
        return this;
    };

}(jQuery));
