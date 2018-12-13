class Carousel {

    /**
     * @param {HTMLElement} element 
     * @param {Object} options 
     * @param {Object} [options.slidesToScroll=1] Nombre d'éléments à faire défiler 
     * @param {Object} [options.slidesVisible=1] Nombre d'éléments visible dans un slide 
     * @param {boolean} [options.loop=false] Doit-on boucler en fin de carousel ?
     * @param {boolean} [options.pagination=false] Acitve la pagination
     * @param {boolean} [options.navigation=true] Active la navigation
     * @param {boolean} [options.infinite=false] Active le scroll infini
     * @param {boolean} [options.autoplay=false] Active le défilement automatique
     * @param {Object} [options.responsive= {} ]
     */
    constructor(element, options = {}) {
        // Déclare nos attributs
        this.element = element
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            slidesVisible: 1,
            loop: false,
            navigation: true,
            pagination: false,
            infinite: false,
            autoplay: false,
            responsive: [
                {
                    slidesVisible: 1,
                    breakpoint: 0
                }
            ]
        }, options)
        let children = [].slice.call(element.children)
        this.currentItem = 0
        this.moveCallbacks = []
        this.offset = 0
        this.responsive = 0

        // Création de la structure HTML
        this.divMain = this.createDivWithClass('cs__slider')
        this.container = this.createDivWithClass('cs__slider__container')
        this.divMain.setAttribute('tabindex', '0')
        this.divMain.appendChild(this.container)
        this.element.appendChild(this.divMain)

        this.items = children.map((child) => {
            let item = this.createDivWithClass('cs__slider__item')
            item.appendChild(child)
            return item
        })

        // Autoplay
        if(this.options.autoplay) {
            if(this.options.infinite) {
                this.options.loop = false
            }
            else {
                this.options.loop = true
            }
            this.isAutoplay()
        }
      
        // Clonage des éléments pour défilement infinit
        if(this.options.infinite) {
            this.options.loop = false
            this.offset = this.options.slidesVisible + this.options.slidesToScroll

            if(this.offset > children.length) {
                this.options.infinite = false
                this.options.loop = true
                console.error("Il n'y a pas assez d'élément pour le carousel", element)
            }
            else {
                this.items = [
                    ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
                    ...this.items,
                    ...this.items.slice(0, this.offset).map(item => item.cloneNode(true)),
                ]
                this.gotoItem(this.offset, false)
            }
        }
        this.items.forEach(item => this.container.appendChild(item))

        this.setStyle()

        if (this.options.navigation || window.innerWidth < 900) {
            if (this.slidesVisible >= children.length) {
                this.options.navigation = false
            }
                this.createNavigation()
        }

        if(this.options.pagination && this.slidesVisible < children.length) {
            this.createPagination()
        }

        // Evenements
        this.moveCallbacks.forEach(callback => callback(this.currentItem))
        this.onWindowResize()
        window.addEventListener('resize', this.onWindowResize.bind(this))
        this.divMain.addEventListener('keyup', e => {
            if(e.key === 'ArrowRight' || e.key === 'Right') {
                this.next()
            } else if (e.key === 'ArrowLeft' || e.key === 'Left') {
                this.prev()
            }
        })
        if(this.options.infinite) {
            this.container.addEventListener('transitionend', this.resetInfinite.bind(this))
        }
    }
    
    /**
     * Applique les bonnes dimensions aux éléments du carousel
     */
    setStyle() {
        let ratio = this.items.length / this.slidesVisible
        this.container.style.width = (ratio * 100) + '%'
        
        this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + '%')
    }

    /**
     * Crée les flèches de navigation d'un carousel
     */
    createNavigation() {
        let nextButton = this.createDivWithClass('cs__slider__next')
        let prevButton = this.createDivWithClass('cs__slider__prev')
        this.element.appendChild(nextButton)
        this.element.appendChild(prevButton)

        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))

        if(this.options.loop === true) {
            return
        }
        this.onMove(index => {
            if(index === 0 ) {
                prevButton.classList.add('cs__slider__prev-hidden')
            } else {
                prevButton.classList.remove('cs__slider__prev-hidden')
            }
            if(index >= this.items.length || this.items[this.currentItem + this.slidesVisible] === undefined) {
                nextButton.classList.add('cs__slider__next-hidden')
            } else {
                nextButton.classList.remove('cs__slider__next-hidden')
            }
        });
    }

    /**
     * Crée la pagination d'un carousel
     */
    createPagination() {
        let pagination = this.createDivWithClass('cs__slider__pagination')
        let buttons = []
        this.divMain.appendChild(pagination)
        for( let i = 0; i < (this.items.length  - 2 * this.offset); i = i + this.options.slidesToScroll) {
            let button = this.createDivWithClass('cs__slider__pagination_btn')
            button.addEventListener('click', () => this.gotoItem(i + this.offset))
            pagination.appendChild(button)
            buttons.push(button)
        }
        this.onMove(index => {
            let count = this.items.length - 2 * this.offset
            let activeButton = buttons[Math.floor(((index - this.offset) % count) / this.options.slidesToScroll)]
            if(activeButton) {
                buttons.forEach(button => button.classList.remove('cs__slider__pagination_btn-active'))
                activeButton.classList.add('cs__slider__pagination__btn-active')
            }
        })

    }

    next() {
        if(this.slidesToScroll > this.slidesVisible) {
            this.options.slidesToScroll = this.options.slidesVisible
        }
        this.gotoItem(this.currentItem + this.slidesToScroll)
    }

    prev() {
        this.gotoItem(this.currentItem - this.slidesToScroll)
    }

    /**
     * Déplace le carousel vers l'élément ciblé
     * @param {number} index 
     * @param {boolean} [animation = true]
     */
    gotoItem (index, animation = true) {
        if(index < 0) {
            if(this.options.loop) {
                index = this.items.length - this.slidesVisible
            } else {
                return
            }
        }
        else if(index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)) {
            if(this.options.loop) {
                index = 0
            } else {
                return
            }
        }
        let translateX = index * -100 / this.items.length
        if(animation === false) {
            this.container.style.transition = 'none'
        }
        this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)'
        this.container.offsetHeight // Force le repaint
        if(animation === false) {
            this.container.style.transition = ''
        }
        this.currentItem = index
        this.moveCallbacks.forEach(callback => callback(index))
    }

    /**
     * Déplace le container pour donner une impression de boucle infinie du carousel
     */
    resetInfinite() {
        if(this.currentItem <= this.options.slidesToScroll) {
            this.gotoItem(this.currentItem + this.items.length - 2 * this.offset, false)
        } else if (this.currentItem >= this.items.length - this.offset) {
            this.gotoItem(this.currentItem - (this.items.length - 2 * this.offset), false)
        }
    }

    /**
     * @param {Carousel moveCallbacks} callback 
     */
    onMove (callback) {
        this.moveCallbacks.push(callback)
    }

    onWindowResize() {
        let resize = 0
        console.log(this.options.navigation)
        this.options.responsive.forEach((setting) => {
            if (window.innerWidth < setting.breakpoint) {
                resize = setting.breakpoint
                this.options.responsive.slidesVisible = setting.slidesVisible
            }
        })
        console.log(resize)
        if (resize !== this.responsive) {
            this.responsive = resize
            this.setStyle()
            this.moveCallbacks.forEach(callback => callback(this.currentItem))
        }
    }
    
    /**
     * @param {string} className
     * @returns {HTMLElement}
     */
    createDivWithClass(className) {
        let div = document.createElement('div')
        div.setAttribute('class', className)
        return div
    }

    /**
     * @returns {number}
     */
    get slidesToScroll() {
        return this.responsive ? 1 : this.options.slidesToScroll
    }

    /**
     * @returns {number}
     */
    get slidesVisible() {
        return this.responsive !== 0 ? this.options.responsive.slidesVisible : this.options.slidesVisible
    }

    isAutoplay() {
        window.setInterval(() => {
            this.next()
        }, 5000)
    }
}

new Carousel(document.querySelector('#cs__slider'), {
    slidesToScroll: 1,
    slidesVisible: 4,
    autoplay: true,
    infinite: true,
    pagination: true,
    navigation: true,
    responsive: [
        {
            slidesVisible: 3,
            breakpoint: 900
        },
        {
            slidesVisible: 1,
            breakpoint: 700
        },
    ]
    })
