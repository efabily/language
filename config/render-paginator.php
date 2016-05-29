<?php
function render_paginator(Zebra_Pagination $paginator)
{
	$paginator->get_page();

    echo get_class($paginator);

	if ($paginator->_properties['total_pages'] <= 1)
	{
		return '';
	}

    echo 'Llego aqui ';
    exit;

	$prevLink = ($paginator->_properties['page'] == 1 ? 'javascript:void(0)' : $paginator->_build_uri($paginator->_properties['page'] - 1));
	$nexLink = ($paginator->_properties['page'] == $paginator->_properties['total_pages'] ? 'javascript:void(0)' : $paginator->_build_uri($paginator->_properties['page'] + 1));
	?>

	<!-- // Progress table -->
   	<nav>
    	<ul class="pagination">

             <li>
              <a href="<?php echo $prevLink?>" aria-label="Previous" >
                <span aria-hidden="true">«</span>
              </a>
            </li> 

        <?php
        $output = '';

        // if the total number of pages is lesser than the number of selectable pages
        if ($paginator->_properties['total_pages'] <= $paginator->_properties['selectable_pages']) {
            // iterate ascendingly or descendingly depending on whether we're showing links in reverse order or not)
            for ($i = ($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] : 1); ($paginator->_properties['reverse'] ? $i >= 1 : $i <= $paginator->_properties['total_pages']); ($paginator->_properties['reverse'] ? $i-- : $i++))
            {
                // render the link for each page
            	            // make sure to highlight the currently selected page
                $output .= '<li '.($paginator->_properties['page'] == $i ? 'class="active"' : '') .'  ><a href="' . $paginator->_build_uri($i) . '" ' . '>' .
                    // apply padding if required
                    ($paginator->_properties['padding'] ? str_pad($i, strlen($paginator->_properties['total_pages']), '0', STR_PAD_LEFT) : $i) . '</a></li>';
            }

            // if the total number of pages is greater than the number of selectable pages
        } else {
            // start with a link to the first or last page, depending if we're displaying links in reverse order or not
        	// highlight if the page is currently selected
            $output .= '<li '.($paginator->_properties['page'] == ($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] : 1) ? 'class="active"' : '').'  ><a href="' . $paginator->_build_uri($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] : 1) . '" ' . '>' .

                        // if padding is required
                            ($paginator->_properties['padding'] ?

                            // apply padding
                            str_pad(($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] : 1), strlen($paginator->_properties['total_pages']), '0', STR_PAD_LEFT) :

                            // show the page number
                            ($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] : 1)) .

                            '</a></li>';

                            // compute the number of adjacent pages to display to the left and right of the currently selected page so
                            // that the currently selected page is always centered
                            $adjacent = floor(($paginator->_properties['selectable_pages'] - 3) / 2);

                            // this number must be at least 1
                            if ($adjacent == 0) $adjacent = 1;

                            // find the page number after we need to show the first "..."
                            // (depending on whether we're showing links in reverse order or not)
                            $scroll_from = ($paginator->_properties['reverse'] ?

                            $paginator->_properties['total_pages'] - ($paginator->_properties['selectable_pages'] - $adjacent) + 1 :

                            $paginator->_properties['selectable_pages'] - $adjacent);

                            // get the page number from where we should start rendering
                            // if displaying links in natural order, then it's "2" because we have already rendered the first page
                            // if we're displaying links in reverse order, then it's total_pages - 1 because we have already rendered the last page
                            $starting_page = ($paginator->_properties['reverse'] ? $paginator->_properties['total_pages'] - 1 : 2);

                            // if the currently selected page is past the point from where we need to scroll,
                            // we need to adjust the value of $starting_page
                            if (

                            ($paginator->_properties['reverse'] && $paginator->_properties['page'] <= $scroll_from) ||
                            (!$paginator->_properties['reverse'] && $paginator->_properties['page'] >= $scroll_from)

                            ) {

                                // by default, the starting_page should be whatever the current page plus/minus $adjacent
                                // depending on whether we're showing links in reverse order or not
                                $starting_page = $paginator->_properties['page'] + ($paginator->_properties['reverse'] ? $adjacent : -$adjacent);

                                // but if that would mean displaying less navigation links than specified in $paginator->_properties['selectable_pages']
                                if (

                                        ($paginator->_properties['reverse'] && $starting_page < ($paginator->_properties['selectable_pages'] - 1)) ||
                                        (!$paginator->_properties['reverse'] && $paginator->_properties['total_pages'] - $starting_page < ($paginator->_properties['selectable_pages'] - 2))

            )

            // adjust the value of $starting_page again
            if ($paginator->_properties['reverse']) $starting_page = $paginator->_properties['selectable_pages'] - 1;

            else $starting_page -= ($paginator->_properties['selectable_pages'] - 2) - ($paginator->_properties['total_pages'] - $starting_page);

            // put the "..." after the link to the first/last page
            // depending on whether we're showing links in reverse order or not
            $output .= '<li><span>&hellip;</span></li>';

                            }

            // get the page number where we should stop rendering
            // by default, this value is the sum of the starting page plus/minus (depending on whether we're showing links
            // in reverse order or not) whatever the number of $paginator->_properties['selectable_pages'] minus 3 (first page,
            // last page and current page)
            $ending_page = $starting_page + (($paginator->_properties['reverse'] ? -1 : 1) * ($paginator->_properties['selectable_pages'] - 3));

            // if we're showing links in natural order and ending page would be greater than the total number of pages minus 1
                            // (minus one because we don't take into account the very last page which we output automatically)
                            // adjust the ending page
                            if ($paginator->_properties['reverse'] && $ending_page < 2) $ending_page = 2;

                            // or, if we're showing links in reverse order, and ending page would be smaller than 2
                            // (2 because we don't take into account the very first page which we output automatically)
                            // adjust the ending page
                            elseif (!$paginator->_properties['reverse'] && $ending_page > $paginator->_properties['total_pages'] - 1) $ending_page = $paginator->_properties['total_pages'] - 1;

                            // render pagination links
                            for ($i = $starting_page; $paginator->_properties['reverse'] ? $i >= $ending_page : $i <= $ending_page; $paginator->_properties['reverse'] ? $i-- : $i++)
                            
                            	// highlight the currently selected page
                            $output .= '<li '.($paginator->_properties['page'] == $i ? 'class="active"' : '').' >
                            		<a href="' . $paginator->_build_uri($i) . '" ' .'>' .

                                    // apply padding if required
                                    ($paginator->_properties['padding'] ? str_pad($i, strlen($paginator->_properties['total_pages']), '0', STR_PAD_LEFT) : $i) .

                                            '</a></li>';

                                            // if we have to, place another "..." at the end, before the link to the last/first page (depending on whether
                            // we're showing links in reverse order or not)
                            if (

                                    ($paginator->_properties['reverse'] && $ending_page > 2) ||
                                    (!$paginator->_properties['reverse'] && $paginator->_properties['total_pages'] - $ending_page > 1)

                            ) $output .= '<li><span>&hellip;</span></li>';

                            // put a link to the last/first page (depending on whether we're showing links in reverse order or not)
                            			// highlight if it is the currently selected page
                            $output .= '<li '.($paginator->_properties['page'] == $i ? 'class="active"' : '') .' >
                                        	<a href="' . $paginator->_build_uri($paginator->_properties['reverse'] ? 1 : $paginator->_properties['total_pages']) . '" ' .'>' .

                            // also, apply padding if necessary
                        ($paginator->_properties['padding'] ? str_pad(($paginator->_properties['reverse'] ? 1 : $paginator->_properties['total_pages']), strlen($paginator->_properties['total_pages']), '0', STR_PAD_LEFT) : ($paginator->_properties['reverse'] ? 1 : $paginator->_properties['total_pages'])) .

                        '</a></li>';
                }
                // return the resulting string
            echo $output; ?>
            <li>
              <a href="<?php echo $nexLink?>" aria-label="Next" >
                <span aria-hidden="true">»</span>
              </a>
            </li>

		</ul>
	</nav>
<?php } ?>