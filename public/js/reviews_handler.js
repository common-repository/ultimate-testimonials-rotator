class Bitcx_UTR_Review_Handler {

   /**
   * @param {HTMLElement} container - The container element for the reviews.
   * @param {Object} term_meta - Metadata for the current term.
   * @param {Array} term_posts - Array of posts for the current term.
   */

  constructor(bitcx_utr_container , bitcx_utr_term_meta , bitcx_utr_term_posts) {
    this.bitcx_utr_reviews_container = bitcx_utr_container
    this.bitcx_utr_term_meta = bitcx_utr_term_meta
    this.bitcx_utr_slides = bitcx_utr_term_posts
    this.bitcx_utr_init();
  }

  /**
   * Initializes the review handler.
   */

  bitcx_utr_init() {
    
    this.bitcx_utr_assingVariables();
    this.bitcx_utr_appendReviews();
    this.bitcx_utr_showButtons();
    this.bitcx_utr_manageColumns();
    this.bitcx_utr_handleNextBtn();
    this.bitcx_utr_handlePrevBtn();
    this.bitcx_utr_handleMouseMove();
    this.bitcx_utr_autoPlay();
  }

  /**
   * Assigns variables from term_meta and initializes some default values.
   */

  bitcx_utr_assingVariables() {
    this.bitcx_utr_desktop_columns = this.bitcx_utr_term_meta.desktop_columns[0];
    this.bitcx_utr_tablet_columns = this.bitcx_utr_term_meta.tablet_columns[0];
    this.bitcx_utr_mobile_columns = this.bitcx_utr_term_meta.mobile_columns[0];
    this.bitcx_utr_delay_time = this.bitcx_utr_term_meta.delay_time[0];
    this.bitcx_utr_reviews = this.bitcx_utr_slides
    this.show_buttons = this.bitcx_utr_term_meta.show_buttons[0];
    this.auto_play = this.bitcx_utr_term_meta.auto_play[0];
    this.infinite_loop = this.bitcx_utr_term_meta.infinite_loop[0];
    this.restrictSlide = 0;
    this.currentSlide = 0;
    this.btn_container = this.bitcx_utr_reviews_container.nextElementSibling
  }

  /**
   * Appends reviews to the container element.
   */

  bitcx_utr_appendReviews() {
    if (this.bitcx_utr_reviews.length > 0) {
      this.bitcx_utr_reviews.forEach((review, index) => {

        let single_review = document.createElement("div");
        single_review.classList.add("bitcx_utr_flex_single_review");

        let title_element = document.createElement("h4");
        title_element.classList.add("bitcx_utr_flex_title");
        title_element.textContent = review.title;
        single_review.appendChild(title_element);

        let button_container_element = document.createElement("div");
        button_container_element.classList.add("bitcx_utr_flex_button_container");

        let button_element = document.createElement("button");
        button_element.addEventListener('click', this.bitcx_utr_handleReadMore.bind(this));
        button_element.classList.add("bitcx_utr_flex_button");
        button_element.textContent = 'Read More';
        button_container_element.appendChild(button_element);

        let content_element = document.createElement("p");
        content_element.classList.add("bitcx_utr_flex_content");
        review.content = review.content.replace(/<[^>]*>/g, '');
        let limited_content = ''
        if (review.content.length < 230) {
          limited_content = review.content
          button_element.style.display = 'none'
        } else {
          limited_content = review.content.trim().slice(0, 230) + "..."
        }
        content_element.textContent = limited_content;

        single_review.appendChild(content_element);
        single_review.appendChild(button_container_element);
        this.bitcx_utr_reviews_container.appendChild(single_review);

        if (index === this.bitcx_utr_reviews.length - 1) {
          let firstChild = this.bitcx_utr_reviews_container.firstElementChild;
          this.bitcx_utr_reviews_container.insertBefore(
            single_review.cloneNode(true),
            firstChild
          );
        }
      });
    } else {
      let error_para = document.createElement("p");
      error_para.classList.add("bitcx_utr_error_para");
      error_para.textContent = "No Reviews Present";
      this.show_buttons = 0;
      this.bitcx_utr_reviews_container.appendChild(error_para);
      this.bitcx_utr_showButtons()

    }
  }

  /**
   * Shows or hides review navigation buttons based on configuration.
   */

  bitcx_utr_showButtons() {
    if (this.show_buttons == 1) {
      this.btn_container.style.display = "flex";
    } else if (this.show_buttons == 0) {
      this.btn_container.style.display = "none";
    } else {
      console.log("Reviews Buttons Error");
    }
  }

  /**
   * Manages the number of columns based on the current window width.
   */

  bitcx_utr_manageColumns() {
    let single_reviews = this.bitcx_utr_reviews_container.children;
    if (single_reviews.length > 0) {
      for (let i = 0; i < single_reviews.length; i++) {
        let element = single_reviews[i];
        if (window.innerWidth >= 1280) {
          element.style.flex = `0 0 calc((100% / ${this.bitcx_utr_desktop_columns}) - 10px)`;
          this.restrictSlide = single_reviews.length - this.bitcx_utr_desktop_columns;
        } else if (window.innerWidth >= 768) {
          element.style.flex = `0 0 calc((100% / ${this.bitcx_utr_tablet_columns}) - 10px)`;
          this.restrictSlide = single_reviews.length - this.bitcx_utr_tablet_columns;
        } else {
          element.style.flex = `0 0 calc((100% / ${this.bitcx_utr_mobile_columns}) - 10px)`;
          this.restrictSlide = single_reviews.length - this.bitcx_utr_mobile_columns;
        }
      }

      if (this.bitcx_utr_reviews.length > 0) {
        this.currentSlide += 1;
        for (let i = 0; i < single_reviews.length; i++) {
          let element = single_reviews[i];
          element.style.transform = `translateX(-${this.currentSlide * single_reviews[0].offsetWidth
            }px)`;
        }
      }
    }
  }

  /**
   * Moves to the next slide.
   * @param {number} e - The value to adjust the translation on the X-axis.
   */

  bitcx_utr_slideNext(e , btn) {
    console.log(btn)
    let translateX_value = 0

    if(e){
      translateX_value = e;
    } 
    if (
      (window.innerWidth >= 1280 && this.bitcx_utr_reviews.length < this.bitcx_utr_desktop_columns) ||
      (window.innerWidth >= 768 && this.bitcx_utr_reviews.length < this.bitcx_utr_tablet_columns) ||
      (window.innerWidth < 768 && this.bitcx_utr_reviews.length < this.bitcx_utr_mobile_columns)
    ) {
      btn.disabled = true;
      return;
    }   

    let single_reviews = this.bitcx_utr_reviews_container.children;


    if (this.infinite_loop == 1) {
      this.bitcx_utr_reviews_container.removeChild(
        this.bitcx_utr_reviews_container.firstElementChild
      );
      let removed_el = this.bitcx_utr_reviews_container.firstElementChild.cloneNode(true);
      
      let btn = removed_el.querySelector(".bitcx_utr_flex_button")
        
      btn.addEventListener('click' , this.bitcx_utr_handleReadMore.bind(this))

      for (let i = 0; i < single_reviews.length; i++) {
        let element = single_reviews[i];
        element.style.transition = "transform 0s ease-in-out";
      }

      this.bitcx_utr_reviews_container.appendChild(removed_el);
      this.restrictSlide += 1;


    }

    if (this.restrictSlide == this.currentSlide) {
      return;
    } else {
      this.currentSlide += 1;

      if (this.infinite_loop == 1) {
        for (let i = 0; i < single_reviews.length; i++) {
          let element = single_reviews[i];
          element.style.transform = `translateX(${0 + translateX_value}px)`;
        }
      }

      for (let i = 0; i < single_reviews.length; i++) {
        let element = single_reviews[i];

        if (this.infinite_loop == 1) {
          element.style.transform = `translateX(-${element.offsetWidth}px)`;
        } else {
          element.style.transform = `translateX(-${this.currentSlide * element.offsetWidth
            }px)`;
        }

        element.style.transition = "transform 0.5s ease-in-out";
      }
    }
  }

  /**
   * Moves to the previous slide.
   * @param {number} e - The value to adjust the translation on the X-axis.
   */

  bitcx_utr_slidePrev(e , btn) {

    let translateX_value = 0

    if(e){
      translateX_value = e;
    }
    if (
      (window.innerWidth >= 1280 && this.bitcx_utr_reviews.length < this.bitcx_utr_desktop_columns) ||
      (window.innerWidth >= 768 && this.bitcx_utr_reviews.length < this.bitcx_utr_tablet_columns) ||
      (window.innerWidth < 768 && this.bitcx_utr_reviews.length < this.bitcx_utr_mobile_columns)
    ) {
      btn.disabled = true;
      return;
    }  

    let single_reviews = this.bitcx_utr_reviews_container.children;
    if (this.infinite_loop == 1) {
      this.bitcx_utr_reviews_container.removeChild(
        this.bitcx_utr_reviews_container.lastElementChild
      );
      let removed_el = this.bitcx_utr_reviews_container.lastElementChild.cloneNode(true);

      let btn = removed_el.querySelector(".bitcx_utr_flex_button")

      btn.addEventListener('click' , this.bitcx_utr_handleReadMore.bind(this))

      let firstChild = this.bitcx_utr_reviews_container.firstElementChild;

      for (let i = 0; i < single_reviews.length; i++) {
        let element = single_reviews[i];
        element.style.transition = "transform 0s ease-in-out";
        console.log(translateX_value)
        element.style.transform = `translateX(-${(single_reviews[0].offsetWidth * 2) - translateX_value}px)`;
      }

      this.bitcx_utr_reviews_container.insertBefore(removed_el, firstChild);
      this.currentSlide -= 1;

      for (let i = 0; i < single_reviews.length; i++) {
        let element = single_reviews[i];
        element.style.transform = `translateX(-${element.offsetWidth}px)`;
        element.style.transition = "transform 0.5s ease-in-out";
      }
    } else {
      if (this.currentSlide == 1) {
        return;
      } else {
        this.currentSlide -= 1;

        for (let i = 0; i < single_reviews.length; i++) {
          let element = single_reviews[i];
          element.style.transform = `translateX(-${this.currentSlide * single_reviews[0].offsetWidth}px)`;
        }
      }
    }
  }

  /**
   * Handles the click event for the "Next" button.
   */

  bitcx_utr_handleNextBtn() {
    let next_btn = this.btn_container.lastElementChild;
  
    next_btn.addEventListener("click", (e) => {
      clearTimeout(this.timeoutId);
      this.timeoutId = setTimeout(() => {
        this.bitcx_utr_autoPlay();
      }, 1000);

      clearInterval(this.interval_id);
      this.bitcx_utr_slideNext(null , next_btn);
    });
  }
  
  /**
   * Handles the click event for the "Previous" button.
   */

  bitcx_utr_handlePrevBtn() {
    let prev_btn = this.btn_container.firstElementChild;

    prev_btn.addEventListener("click", (e) => {
      clearTimeout(this.prevTimeoutId);
      this.prevTimeoutId = setTimeout(() => {
        this.bitcx_utr_autoPlay();
      }, 1000);

      clearInterval(this.interval_id);
      this.bitcx_utr_slidePrev(null , prev_btn);
    });
  }
  
  /**
   * Handles the pointer move event for mouse interactions.
   */

  bitcx_utr_handleMouseMove() {
        const numberOfColumns =
      window.innerWidth >= 1280
        ? this.bitcx_utr_desktop_columns
        : window.innerWidth >= 768
        ? this.bitcx_utr_tablet_columns
        : this.bitcx_utr_mobile_columns;

    if (this.bitcx_utr_reviews.length <= numberOfColumns) {
      return; // Do not autoplay if reviews are less than or equal to columns
    }
    const container = this.bitcx_utr_reviews_container.parentElement;
    let single_reviews = this.bitcx_utr_reviews_container.children;
    let pointerdown = 0;
    let totalDistance = 0;
    let initialTranslateXValues = [];
  
    for (let i = 0; i < single_reviews.length; i++) {
      const transformStyle = window.getComputedStyle(single_reviews[i]).transform;
      const matrix = new DOMMatrix(transformStyle);
      initialTranslateXValues[i] = matrix.m41;
    }
  
    if (container) {
      container.addEventListener("pointerdown", (event) => {
        pointerdown = event.clientX;
        container.addEventListener("pointermove", handlePointerMove);
        clearInterval(this.interval_id);
      });
  
      const handlePointerMove = (event) => {
        clearInterval(this.interval_id);
        for (let i = 0; i < single_reviews.length; i++) {
          single_reviews[i].style.transition = "none";
        }
        const pointerup = event.clientX;
        totalDistance = pointerup - pointerdown;
        for (let i = 0; i < single_reviews.length; i++) {
          single_reviews[i].style.transform = `translateX(${initialTranslateXValues[i] + totalDistance}px)`;
        }
      };
  
      const handlePointerUp = () => {
        container.removeEventListener("pointermove", handlePointerMove);
        this.bitcx_utr_autoPlay()
  
        for (let i = 0; i < single_reviews.length; i++) {
          single_reviews[i].style.transition = "transform 0.1s ease"; 
        }
  
        if (totalDistance > 0) {
          this.bitcx_utr_slidePrev(totalDistance);
        } else if (totalDistance < 0) {
          this.bitcx_utr_slideNext(totalDistance);
        }
  
        totalDistance = 0;
      };
  
      container.addEventListener("pointerup", handlePointerUp);
      container.addEventListener("pointercancel", handlePointerUp);
    }
  }
  
  /**
   * Initiates autoplay based on configuration.
   */
  
  bitcx_utr_autoPlay() {
      const numberOfColumns =
      window.innerWidth >= 1280
        ? this.bitcx_utr_desktop_columns
        : window.innerWidth >= 768
        ? this.bitcx_utr_tablet_columns
        : this.bitcx_utr_mobile_columns;

    if (this.bitcx_utr_reviews.length <= numberOfColumns) {
      return; // Do not autoplay if reviews are less than or equal to columns
    }
    if (this.auto_play == 1) {
      this.interval_id = setInterval(() => {
        this.bitcx_utr_slideNext();
      }, this.bitcx_utr_delay_time * 1000);
    } else if (this.auto_play == 0) {
      return;
    } else {
      console.log("AutoPlay Error");
    }
  }

  /**
   * Handles the "Read More" button click event to show more content.
   * @param {Event} e - The click event.
   */

  bitcx_utr_handleReadMore(e) {
  clearInterval(this.interval_id);
  clearTimeout(this.timeoutId);
  let btn = e.target;
  let content_element = btn.parentElement.previousElementSibling;
  if (btn.textContent === "Read More") {
    let el = this.bitcx_utr_reviews.filter((review) => {
      return (review.content.trim().slice(0, 230) + "...") === content_element.textContent;
    });
    content_element.textContent = el[0].content;
    content_element.style.overflow = "auto";
    btn.textContent = "Read Less";
    } else if (btn.textContent === "Read Less") {
    content_element.textContent = content_element.textContent.trim().slice(0, 230) + "...";
    content_element.style.overflow = "hidden";
    btn.textContent = "Read More";
    this.bitcx_utr_autoPlay();
    }
  };
};


// Instantiate bitcx_utr_Review_Handler for each container element.

const terms = template_flex_reviews_vars.taxonomies_data[0].terms;
terms.forEach((term) => {
 
  const container = term.slug + '_' + term.id;
  const bitcx_utr_reviews_containers = document.querySelectorAll(
    '.' + container
  );
  bitcx_utr_reviews_containers.forEach((container) => {
      new Bitcx_UTR_Review_Handler(container , term.term_meta , term.posts);
    })

})



