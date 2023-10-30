#. Main function
    #. Filter populate 
        - All [ with all term id ]
        - li [ with term id ]

    #. Post Populate

        -1) Shortcode declare 
            - Filter template 
            - Post fetch div generate 

        -2) Get Post wordPress ajax function declare 
            - defined whats parameter you need 
            - sanitize all parameter 
            - Wp query run 
            - Post template defined 
                - Template file error defined
                    - Post all element need to show 
                    - Post pagination here we need to defined 
                    - post return as json or no post found hanlde 
    
        -3) Jquery Script 
            - Need to extend a function where store all parameter by using we call ajax 
                - need to create common function get_post() [ Parameter = full object need to pass to getch the post ]
                    - filter_id + page + post_type + tax + term 
                - Return json we need to add on post fetch div


===============================================================================

   -1) Shortcode declare 
            - Filter template 
            - Post fetch div generate 