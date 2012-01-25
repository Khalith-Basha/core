    var photoIndex = 0;
    function gebi(id) { return document.getElementById(id); }
    function ce(name) { return document.createElement(name); }
    function re(id) {
        var e = gebi(id);
        e.parentNode.removeChild(e);
    }
    function addNewPhoto() {
        var max = <?php
		echo osc_max_images_per_item(); ?>;
        var num_img = $('input[name="photos[]"]').size() + $("a.delete").size();
        if((max!=0 && num_img<max) || max==0) {
            var id = 'p-' + photoIndex++;

            var i = ce('input');
            i.setAttribute('type', 'file');
            i.setAttribute('name', 'photos[]');

            var a = ce('a');
            a.style.fontSize = 'x-small';
            a.style.paddingLeft = '10px';
            a.setAttribute('href', '#');
            a.setAttribute('divid', id);
            a.onclick = function() { re(this.getAttribute('divid')); return false; }
            a.appendChild(document.createTextNode('<?php
		_e('Remove'); ?>'));

            var d = ce('div');
            d.setAttribute('id', id);
            d.setAttribute('style','padding: 4px 0;')

            d.appendChild(i);
            d.appendChild(a);

            gebi('photos').appendChild(d);

        } else {
            alert('<?php
		_e('Sorry, you have reached the maximum number of images per ad'); ?>');
        }
    }
    // Listener: automatically add new file field when the visible ones are full.
    setInterval("add_file_field()", 250);
    /**
     * Timed: if there are no empty file fields, add new file field.
     */
    function add_file_field() {
        var count = 0;
        $('input[name="photos[]"]').each(function(index) {
            if ( $(this).val() == '' ) {
                count++;
            }
        });
        var max = <?php
		echo osc_max_images_per_item(); ?>;
        var num_img = $('input[name="photos[]"]').size() + $("a.delete").size();
        if (count == 0 && (max==0 || (max!=0 && num_img<max))) {
            addNewPhoto();
        }
    }

