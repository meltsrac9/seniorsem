<?php
/******************************************************
* Notes for Use
*
* 1. Create the object with the pg_list() Constructor Function - parameters below
*       Required Parameters
*       $query - the database query to produce the data to list
*       $id_field - the primary key field in the data 
*       $default_sort_by - The sort by field for the initial display 
*       $default_sort_dir -  asc or desc
*
*       Optional Parameters (have default value if ommitted)
*       $css_class_th - css class for the <th> header row tags
*       $css_class_td - css class for the <td> tags under the header row
*       $cellspacing - for the table
*       $cellpadding - for the table
*       $uses_paging - true or false -- whether all results are displayed vs using pagination
*       $page_length - if using pagination, how many results per page
*       $css_class_row - css class for odd rows
*       $css_class_even - css class for even rows - having odd and even allows shading every other row
*       $css_class_hilite - css class for mouseover row hover states
*
* 2. Add the columns with add_column() method - parameters below
*       Required Parameters
*       $column_name - name of the column's data from the query
*       $column_header - text to display to title the column
*
*       Optional Parameters (have default value if ommitted)
*       $format - specifies which formatting to use to transform the column value
*               - the $type parameter below offers more powerful column formatting
*       $css_class - css class to use only for the tds in this column 
*       $column_url - turns column value into a link with this url 
*                   - use special notation to embed %%%column_name%%% data from column_name into the URL
*       $on_click - value for the onclick='value'
*       $sortable - true if this column is sortable
*       $blank_message - message for column in case it is empty
*       $type -  custom formatting type for column - any data fields in the datebase record can be included into the column
* 
* 3. Initilize with init_list() method
*
* 4. Pour the html into your page with get_html() method - this will create the table

***********************************************************************************************************/

class pg_list  {

   var $query;
   var $id_field;
   var $sort_url;
   var $default_sort_by;
   var $default_sort_dir;
   var $sort_by;
   var $sort_dir;
   var $css_class_th;
   var $css_class_td;
   var $css_class_row;
   var $css_class_even;
   var $css_class_hilite;
   var $cellspacing;
   var $cellpadding;
   var $empty_message;
   var $columns;
   var $limit; //overrides what is sent in page values
   var $uses_paging;
   var $page_length;
   var $page;
   var $page_count;

   var $is_initialized;
   var $row_count;
   var $kept_rows;  // array of filtered rows -- the my_sql result records that don't get axed by the filter


    //Constructor Function
    function pg_list($query, $id_field, $default_sort_by, $default_sort_dir, $css_class_th = '', $css_class_td = '',
            $cellspacing='1', $cellpadding='4', $uses_paging = false, $page_length='50',
            $css_class_row = '', $css_class_even = '', $css_class_hilite = ''){

        $this->query = $query;
        $this->id_field = $id_field;
        $this->default_sort_by = $default_sort_by;
        $this->default_sort_dir = $default_sort_dir;
        $this->css_class_th = $css_class_th;
        $this->css_class_td = $css_class_td;
        $this->cellspacing = $cellspacing;
        $this->cellpadding = $cellpadding;
        $this->uses_paging = $uses_paging;
        $this->page_length = $page_length;
        $this->css_class_row = $css_class_row;
        $this->css_class_even = $css_class_even;
        $this->css_class_hilite = $css_class_hilite;
        
        $this->kept_rows = array();
        
    }//end function pg_list

   function init_list(){
      $_GETPOST = array_merge($_GET, $_POST);

      // check for saved page info, but only if we are told to -- we don't want to rember the last state of one list when moving to another list in the same app
      // so we will use a special flag to enable this 
      /*
      Send propagate_pageable_list_in_session=yes from a page that returns to the list to remember where the list was at 
      One would be tempted to always reload pagination state from session, but it is often desirable to forget last pagination too like when doing a search
      However, when returning to a search listing from a link in that listing, for example, it is good to remember the pagination
      */
      
      if ( $_GETPOST['propagate_pageable_list_in_session'] && ($_SESSION['current_list'] == $_SERVER['PHP_SELF']) ) {
          $this->page = $_SESSION['current_page'];
          $this->page_count = $_SESSION['current_page_count'];
          $this->page_length = $_SESSION['current_page_length'];
          $this->sort_by = $_SESSION['current_sort_by'];
          $this->sort_dir = $_SESSION['current_sort_dir'];
      }
      // incoming paramaters will override the session ones
      
      if ($_GETPOST['page']){
         $this->page = $_GETPOST['page'];
      }
      if ($_GETPOST['page_count']){
         $this->page_count = $_GETPOST['page_count'];
      }
      if ($_GETPOST['page_length']){
         $this->page_length = $_GETPOST['page_length'];
      }
      
      if (! $this->page_length){
         $this->page_length = 50;
      }
      if (!$this->page){
         $this->page = 1;
      }
      

      //Figure out the sort by and sort order;
      if ($_GETPOST['sort_by']){
         $this->sort_by = $_GETPOST['sort_by'];
         $this->sort_dir = $_GETPOST['sort_dir'];
      }

      //We checked the request supervariable too. Now we give up.
      if (!$this->sort_by){
         $this->sort_by = $this->default_sort_by;
         $this->sort_dir = $this->default_sort_dir;
      }
      
      $this->sort_url = $_SERVER['PHP_SELF'];
      
      // This is so in the propagate_pageable_list_in_session clause above, we can avoid potentially using session info from a different list
      $_SESSION['current_list'] = $_SERVER['PHP_SELF'];
      // Save the paging data
      $_SESSION['current_page'] = $this->page;
      $_SESSION['current_page_count'] = $this->page_count;
      $_SESSION['current_page_length'] = $this->page_length;
      $_SESSION['current_sort_by'] = $this->sort_by;
      $_SESSION['current_sort_dir'] = $this->sort_dir;
      
      $this->is_initialized = true;
   }

   function get_html(){
      if (!$this->is_initialized){
         die ("You must initialize the list by calling init_list() before calling get_html().");
      }
      if (!isset($r)) {
         $r = "";
      }
      
      // used in sortable column headers and the pagination buttons
      $qs_to_propagate = $this->propagate_extra_get_items();

      // Do the hilite script
      $r .= "\n<SCRIPT>\n"
            . "function hilite_row (row, which, css_class) {\n"
            . " if (which) {\n"
            . " row.className = '" . $this->css_class_hilite . "';\n"
            . " } else {\n"
            . " row.className = css_class;\n"
            . " }\n"
            . " }\n"
            . "</SCRIPT>\n";

      $r.= "<table cellpadding='" . $this->cellpadding . "' cellspacing='" . $this->cellspacing . "'>\n";
      
      
      $sort_by = $this->sort_by;
      $sort_dir = $this->sort_dir;
      
      //if we have a sort_url, it's because we need to pass arguments.
      if ($this->sort_url){
         if (strpos ($this->sort_url, '?') === false) {
            $url_delimiter = '?';
         } else {
            $url_delimiter = '&';
         }
      } 
      else {
         $this->sort_url = $_SERVER['PHP_SELF'];
         $url_delimiter = '?';
      }//end if
      
      
      // CDK special sorting stuff -- more than one column involved
      switch ($sort_by){
          case 'date':   
              $sort_by_save = $sort_by;
              $sort_dir_save = $sort_dir;
              $sort_by = ' date '.$sort_dir.',timestamp_absolute '.$sort_dir;
              $sort_dir = '';
              break; 
          case 'time':
              $sort_by_save = $sort_by;
              $sort_dir_save = $sort_dir;
              $sort_by = 'timestamp_absolute '.$sort_dir.',date '.$sort_dir;
              $sort_dir = '';
              break;
    
      }//end switch
    
      //Now, parse the rows
      $query = $this->query;
      
      $query.= " ORDER BY $sort_by $sort_dir ";
      
      if ( $sort_dir_save ) {
        // put back in place so next sort dir can be determined
        $sort_dir = $sort_dir_save; 
      }
      if ( $sort_by_save ) {
        // put back in place so special sorts above don't foul things up
        $sort_by = $sort_by_save; 
      }
      
      
      $this->row_count = 0;
      $result = lib::db_query($query);
      
      
      // With the addition of filtering in the get_row function we now have to do page count and pagination stuff here
      // also this first pass over the result set keeps only the rows that survive the filter
      
      if ($result->num_rows > 0){
         while ($row = $result->fetch_assoc()){
            // main thing here is rows are counted
            $cruft = $this->get_row($row,'filtering');
         }//end while
      } 
      mysqli_free_result($result);
      
      $total_results = $this->row_count;
      
      //$this->kept_rows[] now is array of rows that survived the filter in the get_row function
      //$total_results is size of this array
      
     
      if (($total_results > $this->page_length) && ($this->page_length > 0)){
            $this->page_count = ceil($total_results / $this->page_length);
      } 
      else {
            $this->page_count = 1;
      }
      
      global $total_results_from_pageeable_list;
      $total_results_from_pageeable_list = $total_results;
      
      if ($this->uses_paging && ($total_results>$this->page_length) ) {
        $page_count_display = '&nbsp;<span style="font-size:.75em;font-weight:bold;">'.$total_results.' results - showing page '.$this->page.' of '.$this->page_count.'</span>';
      }
      else {
        $all_results_display ='&nbsp;<span style="font-size:.75em;font-weight:bold;">showing all '.$total_results.' results</span>';
      }
      
      
      //Determine the limits
      if ($this->uses_paging){
         //Do the page limit
         $page_offset = ($this->page - 1) * $this->page_length;
         $display_start = $page_offset;
         $display_stop =  $page_offset +  $this->page_length;
      } 
      else {
         $display_start = 0;
         $display_stop =  $total_results;
      }
      
      $pb = ''; // pagination buttons
      if ($this->uses_paging && ($total_results>$this->page_length) ) {
          
            
          $pb .= "<tr><td colspan='" . sizeof ($this->columns) . "'>".$page_count_display."</td></tr>";
    
          //draw the first page and previous buttons, but disabled if this IS the first page.
          $pb .= "<tr><td colspan='" . sizeof ($this->columns) . "'><table><tr>";
          if ($this->page == 1) {
             $pb .= "<td class='tiny'><input class='disabled' type='button' disabled value=' << '></td>";
             $pb .="<td class='tiny'><input class='disabled' type='button' disabled value=' < '></td>";
          } 
          else {
             $pb .= "<td class='tiny'><input type='button' name='page_move' title='Go Back to Page 1' value = ' << ' onclick='location.href=(\""
                 . $this->get_page_url('first').$qs_to_propagate . "\");'></td>";
             $pb .= "<td class='tiny'><input type='button' name='page_move' title='Go Back One Page' value = ' < ' onclick='location.href=(\""
                 . $this->get_page_url('previous').$qs_to_propagate . "\");'></td>";
          } //END if
          
          
          //draw the last page and next buttons, but disabled if this IS the last page.
          if ($this->page == $this->page_count) {
             $pb .= "<td class='tiny'><input class='disabled' type='button' disabled value=' > '></td>";
             $pb .= "<td class='tiny'><input class='disabled' type='button' disabled value=' >> '></td>";
          } 
          else {
             $pb .= "<td class='tiny'><input type='button' name='page_move' title='Advance One Page' value = ' > ' onclick='location.href=(\"". $this->get_page_url('next').$qs_to_propagate . "\");'></td>";
             $pb .= "<td class='tiny'><input type='button' name='page_move' title='Advance to the Last Page' value = ' >> ' onclick='location.href=(\"". $this->get_page_url('last').$qs_to_propagate . "\");'></td>";
          } //END if
          $pb .= "</tr></table></td></tr>";
      } // end if uses paging
      else {
        $pb .= "<tr><td colspan='" . sizeof ($this->columns) . "'>".$all_results_display."</td></tr>";
      }
      
      $r .= $pb; // paging buttons before data
      
      if ( $total_results > 0 ) {
          //Do a header row
          $r.= "  <tr class='" . $this->css_class_tr . "'>\n";
          foreach ($this->columns as $col){
     
             //Can we sort on this field?
             if ($col->sortable){ 
                //Yes. Draw the clickable header
                if ($col->column_name == $sort_by){
                   $currentSortDir = ($sort_dir == 'DESC' ? 'ASC' : 'DESC');
                }

                $r.= "    <th class='$this->css_class_th'><a href='" . $this->sort_url . $url_delimiter . "sort_by=" . $col->column_name . "&sort_dir=$currentSortDir" . "&page=" . $this->page . "&page_count=" . $this->page_count . "&page_length=" . $this->page_length . $qs_to_propagate. "'>" . $col->column_header . "</a></th>\n";
             } 
             else {
                //No. Just draw the name.
                $r.= "    <th class='$this->css_class_th'>" . $col->column_header . "</th>\n";
             }//end if
          }//end foreach
          $r.= "  </tr>\n";
      }
      
      // now the actual rows
      if ( $total_results>0 ){
         for ($i=$display_start ; $i< $display_stop ; $i++ ) {
            if ( isset($this->kept_rows[$i]) ) {
              $r.= $this->get_row($this->kept_rows[$i]);
            }
         }
      } 
      else {
         $r.= $this->get_empty_row();
      }
     
     	$r .= $pb;  // paging buttons after data
    
      $r.= "</table>";

      $this->sort_by="";
      return $r;
   }//end function

   function get_row($row,$just_filtering=''){
      //Begin the row
      
      // no filter implemented by default
      $include_this_row = true;
      
      global $apply_filter_for_visit_history;
      if( $apply_filter_for_visit_history ) {
        //may set inclusion of row to false
        require '../admin/admin_visits_history_filter.inc';
      }
          
      if (  $include_this_row ) {
      
            if (($this->row_count % 2) != 0) {
               $r = "  <tr class='" . $this->css_class_row . "'"
                                             . " onMouseOver='hilite_row(this, true, \"". $this->css_class_row . "\");'"
                                             . " onMouseOut='hilite_row(this, false, \"" . $this->css_class_row . "\");'>";
            } else { // if even row
                $r = "  <tr class='" . $this->css_class_even . "'"
                                             . " onMouseOver='hilite_row(this, true, \"" . $this->css_class_even . "\");'"
                                             . " onMouseOut='hilite_row(this, false, \"" . $this->css_class_even . "\");'>";
            } // end else
            
            $this->row_count ++;
            
            if ($just_filtering) {
              $this->kept_rows[] = $row;
            }
      
            foreach ($this->columns as $col){
               //if the url has an %%%id%%% token, then replace with the id
               $url = $col->column_url;
               $on_click = $col->on_click;
               
               //Check for special %%% tokens
               $pattern = "%%%[A-Za-z0-9_]+%%%";
               $regs = array();
               
               //Check in the URL
               while (ereg($pattern, $url, $regs)){
                  $token = $regs[0];
                  $token = str_replace('%%%', '', $token);
                  $url = str_replace($regs[0], $row[$token], $url);
               }
      
               //Check in the onClick
               while (ereg($pattern, $on_click, $regs)){
                  $token = $regs[0];
                  $token = str_replace('%%%', '', $token);
                  $on_click = str_replace($regs[0], $row[$token], $on_click);
               }
               
               if ( $col->type == 'people_action_links' ) {
                  // provide edit and delete links
                  $value  = "<a href=\"person_form.php?task=edit&ppl_id=".$row[ppl_id]."\" >edit</a>";
                  $value .= "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
                  $value .= "<a href=\"person_form.php?task=delete&ppl_id=".$row[ppl_id]."\" >delete</a>";
               }
               else if($col->type == 'some_other_custom_column') {
                   // something similar to the above for another column in the same page
                   // or even another column in a different listing page
                  $value = "blagh blagh";
               }
               else {
               	 $value = $col->format_value($row[$col->column_name]);
               }
               
               if ($value === '') {
                  $value = $col->blank_message;
               }
               
               $css_class = $col->css_class;
               //Begin the table cell
               $r.= "    <td class='" . $css_class . "'>";
               
               //was a url or an onClick passed?
               if ($url || $on_click) {
                  //draw it with a linky-loo
                  $r.= "<a href='$url' onClick='$on_click'>$value</a>";
               } else {
                  //just draw the value
                  $r.= $value;
               }//end if
               
               //End the table cell
               $r.= "</td>\n";
             }//end foreach
            
            $r.= "  </tr>";
      } // if this row is to be included subject to filter(s)
      return $r;
   }//end function get_row();

    function get_empty_row(){
        $empty_message = $this->empty_message;
        if (!$empty_message) $empty_message = 'There are currently no records in the database.';
        $colspan = count($this->columns);
        $r = "    <tr class='row'><td colspan='$colspan' class='$this->css_class_td'><i>$empty_message</i></td></tr>\n";
        return $r;
    }

    function add_column($column_name, $column_header, $format='', $css_class='', $column_url = '', $on_click = '', $sortable = true, $blank_message = '', $type = ''){
        $c = new list_column($column_name, $column_header, $sortable, $column_url, $on_click, $format, $css_class, $blank_message, $type);
        $this->columns[] = $c;
    }//end function

    function get_page_url($page){
        if (!$this->is_initialized){
            die ("You must initialize the list by calling init_list() before calling get_page_url().");
        }
        $sort_by = $this->sort_by;
        $sort_dir = $this->sort_dir;
        $bad = false;
        //if we have a sort_url, it's because we need to pass arguments.
        // we may have a sort_url, or may not
        // if we do, it may or may not have already used the '?' char to set parameters
        if ($this->sort_url){
            if (strpos ($this->sort_url, '?') === false) {
               $url_delimiter = '?';
            } else {
               $url_delimiter = '&';
            }
        } else {
            $this->sort_url = $SERVER['PHP_SELF'];
            $url_delimiter = '?';
        }//end if
        
        if ($page == 'unspecified'){
            $r = $this->sort_url . $url_delimiter . "sort_by=" . $sort_by . "&sort_dir=" . $sort_dir . "&page_count=" . $this->page_count . "&page_length=" . $this->page_length;
            return $r;
        }

        if ($page == 'first'){
            $page = 1;
            if ($this->page == 1){
                $bad = true;
            }
        }//end if

        if ($page == 'last') {
            $page = $this->page_count;
            if ($this->page == $this->page_count){
                $bad = true;
            }
        }//end if

        if ($page == 'previous'){
            $page = $this->page - 1;
            if ($page < 1){
                $bad = true;
            }//end if

        }//end if

        if ($page == 'next'){
            $page = $this->page + 1;
            if ($page > $this->page_count){
                $bad = true;
            }//end if

        }//end if

        if ($bad == false){
            $r = $this->sort_url . $url_delimiter . "sort_by=" . $sort_by . "&sort_dir=" . $sort_dir . "&page=" . $page . "&page_count=" . $this->page_count . "&page_length=" . $this->page_length;
            return $r;
        } 
        else {
            return false;
        }//end if

    }//end function get_page_url()
    
    function propagate_extra_get_items() {
        // propagates extra query string items other than what this class uses
        $get_preserve_items = array();
        foreach ( $_GET as $key=>$get_value ) {
          if ( $key != 'sort_by' && $key != 'sort_dir' && $key != 'page' && $key != 'page_count'  && $key != 'page_length' ) {
            // then its something other than what this pageable list tool is using
            if (!is_array($get_value)) {
              $get_preserve_items[] = $key.'='.urlencode(stripslashes($get_value));
            }
          }
        }
        if (  count($get_preserve_items) ) {
          return '&'.implode('&',$get_preserve_items);
        } 
    } // end function
    
}//end class pg_list

class list_column {
    var $column_name;
    var $column_header;
    var $sortable;
    var $column_url;
    var $on_click;
    var $format;
    var $css_class;
    var $blank_message;
    var $type;

    function list_column($column_name, $column_header, $sortable, $column_url, $on_click, $format, $css_class, $blank_message, $type){
        $this->column_name = $column_name;
        $this->column_header = $column_header;
        $this->sortable = $sortable;
        $this->column_url = $column_url;
        $this->on_click = $on_click;
        $this->format = $format;
        $this->css_class = $css_class;
        $this->blank_message = $blank_message;
        $this->type = $type;
    }// end function

    function format_value($value){
        switch ($this->format){
            case '':
                return $value;
                break;
            case 'date':
                return date("Y-m-d", $value);
                break;
            case 'datetime':
            	 return date("Y-m-d h:i a", $value);
                break;
            case 'sortable_date':
                return date("Y-m-d", strtotime($value));
                break;
            case 'convert_date':
                // converts from sortable database format to standard human readable
                return date('m/d/Y',strtotime($value));
                break;
            case 'expires':
                return date('m/d/Y',$value);
                break;
            case 'GMT_to_local':
                $timestamp = strtotime($value .  ' GMT');
                return date('Y-m-d h:i:s a',$timestamp);
                break;  
                
            case 'yesno':
            	 // turn a 0/1 field into Yes/No
                if ($value){
                    return 'Yes';
                } 
                else {
                    return 'No';
                }
                break;
            case 'abbreviated':
                if(strlen($value) > 10){
                    $value = substr($value, 0, 9) . "...";
                }// end if
                return $value;
                break;
             case 'young_or_old':
                if( $value > 60 ){
                	$value = 'Old';
                }
                else {
                	$value = 'Young';	
                }
                return $value;
                break;
            
            default:
                die ("There is no format called '$this->format' for columns.");

        }//end switch

    }
}//end class list_column



?>
