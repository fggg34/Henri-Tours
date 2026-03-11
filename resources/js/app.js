import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import flatpickr from 'flatpickr';
import TomSelect from 'tom-select';
import Swiper from 'swiper';
import { Navigation, EffectFade } from 'swiper/modules';
import 'flatpickr/dist/flatpickr.min.css';
import 'tom-select/dist/css/tom-select.css';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/effect-fade';

Alpine.plugin(collapse);
window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.TomSelect = TomSelect;
window.Swiper = Swiper;
window.SwiperNavigation = Navigation;
window.SwiperEffectFade = EffectFade;

Alpine.data('bookingSidebar', (config) => ({
  priceUrl: config.priceUrl,
  datesUrl: config.datesUrl,
  slug: config.slug,
  maxGuests: config.maxGuests,
  createUrl: config.createUrl,
  useCalendar: config.useCalendar,
  basePrice: config.basePrice || 0,
  initialDate: config.initialDate || '',
  initialGuests: Math.max(1, Math.min(config.maxGuests, parseInt(config.initialGuests, 10) || 1)),
  guests: 1,
  selectedDate: '',
  pricePerPerson: config.basePrice || 0,
  originalPricePerPerson: config.basePrice || 0,
  discountApplied: null,
  total: (config.basePrice || 0) * 1,
  currency: (config.currency === 'EUR' || !config.currency ? '€' : config.currency),
  tierLabel: '',
  loading: true,
  participantsOpen: false,
  availableDates: [],
  closedDates: [],
  fp: null,
  async init() {
    this.guests = this.initialGuests;
    this.selectedDate = this.initialDate || '';
    if (!this.useCalendar) return;
    this.$nextTick(() => {
      this.initFlatpickr();
      if (this.initialDate && this.fp) this.fp.setDate(this.initialDate);
    });
    this.fetchDates().then(() => {
      if (this.fp) this.fp.set('disable', this.closedDates);
      if (this.initialDate && this.fp) this.fp.setDate(this.initialDate);
    });
    this.updatePrice();
    this.$watch('guests', () => this.updatePrice());
  },
  async fetchDates() {
    try {
      const from = new Date();
      const to = new Date();
      to.setMonth(to.getMonth() + 3);
      const url = `${this.datesUrl}?from=${from.toISOString().slice(0, 10)}&to=${to.toISOString().slice(0, 10)}`;
      const res = await fetch(url);
      if (!res.ok) throw new Error('Failed to load dates');
      const data = await res.json();
      const raw = Array.isArray(data.dates) ? data.dates : [];
      this.availableDates = raw
        .filter(d => d && (d.is_available === true || (d.available_spots != null && d.available_spots > 0)))
        .map(d => d.date_formatted || d.date || null)
        .filter(Boolean);
      this.closedDates = Array.isArray(data.closed_dates) ? data.closed_dates : [];
    } catch (e) {
      this.closedDates = [];
    }
    this.loading = false;
  },
  buildFallbackDates(days) {
    const out = [];
    const d = new Date();
    for (let i = 0; i < days; i++) {
      const copy = new Date(d);
      copy.setDate(copy.getDate() + i);
      out.push(copy.toISOString().slice(0, 10));
    }
    return out;
  },
  initFlatpickr() {
    if (!window.flatpickr || !this.$refs.dateInput || !this.$refs.calendarContainer) return;
    const self = this;
    const maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() + 1);
    this.fp = window.flatpickr(this.$refs.dateInput, {
      dateFormat: 'Y-m-d',
      minDate: 'today',
      maxDate: maxDate,
      disable: this.closedDates,
      static: true,
      appendTo: this.$refs.calendarContainer,
      onChange(selected, dateStr) {
        self.selectedDate = dateStr || '';
        self.updatePrice();
      },
    });
  },
  async updatePrice() {
    try {
      let url = `${this.priceUrl}?guests=${this.guests}`;
      if (this.selectedDate) url += `&date=${this.selectedDate}`;
      const res = await fetch(url);
      const data = await res.json();
      this.pricePerPerson = data.price_per_person ?? 0;
      this.total = data.total ?? 0;
      this.originalPricePerPerson = data.original_price_per_person ?? this.pricePerPerson;
      this.discountApplied = data.discount_applied || null;
      const apiCurrency = String(data.currency || '').toUpperCase();
      if (apiCurrency && apiCurrency !== 'EUR') {
        this.currency = data.currency;
      }
      this.tierLabel = data.tier_applied ? 'Group discount applied' : '';
    } catch (e) {
      this.pricePerPerson = this.basePrice || 0;
      this.total = this.pricePerPerson * this.guests;
      this.originalPricePerPerson = this.pricePerPerson;
      this.discountApplied = null;
    }
  },
}));

Alpine.data('mobileBookingBar', () => ({
  visible: true,
  init() {
    const target = document.getElementById('booking-form');
    if (!target) return;
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => { this.visible = !e.isIntersecting; });
      },
      { threshold: 0.1, rootMargin: '-60px 0px 0px 0px' }
    );
    observer.observe(target);
  },
  scrollToBooking() {
    document.getElementById('booking-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  },
}));

Alpine.data('searchSidebarDate', (initialDate = '') => ({
  fp: null,
  init() {
    this.$nextTick(() => this.initFlatpickr());
  },
  initFlatpickr() {
    if (!window.flatpickr || !this.$refs.dateInput) return;
    const maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() + 1);
    this.fp = window.flatpickr(this.$refs.dateInput, {
      dateFormat: 'Y-m-d',
      minDate: 'today',
      maxDate: maxDate,
      allowInput: false,
    });
    if (initialDate) this.fp.setDate(initialDate);
  },
}));

Alpine.data('heroSearchForm', (config) => ({
  action: config.action,
  cities: config.cities || [],
  initialCity: config.initialCity || '',
  initialDate: config.initialDate || '',
  initialAdults: Math.max(1, parseInt(config.initialAdults, 10) || 2),
  selectedCity: config.initialCity || '',
  selectedDate: config.initialDate || '',
  adults: Math.max(1, parseInt(config.initialAdults, 10) || 2),
  cityOpen: false,
  dateOpen: false,
  adultsOpen: false,
  fp: null,
  get selectedCityName() {
    if (!this.selectedCity) return '';
    const c = this.cities.find(x => x.slug === this.selectedCity);
    return c ? c.name : '';
  },
  init() {
    this.selectedCity = this.initialCity;
    this.selectedDate = this.initialDate;
    this.adults = this.initialAdults;
    this.$nextTick(() => this.initFlatpickr());
  },
  selectCity(slug) {
    this.selectedCity = slug || '';
  },
  toggleDatePicker() {
    if (!this.fp) return;
    if (this.dateOpen) {
      this.fp.close();
    } else {
      this.fp.open();
    }
  },
  formatDate(ymd) {
    if (!ymd) return '';
    const d = new Date(ymd + 'T00:00:00');
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  },
  initFlatpickr() {
    if (!window.flatpickr || !this.$refs.dateInput || !this.$refs.dateContainer) return;
    const self = this;
    const maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() + 1);
    this.fp = window.flatpickr(this.$refs.dateInput, {
      dateFormat: 'Y-m-d',
      minDate: 'today',
      maxDate: maxDate,
      appendTo: this.$refs.dateContainer,
      static: true,
      onOpen() { self.dateOpen = true; },
      onClose() { self.dateOpen = false; },
      onChange(selected, dateStr) {
        self.selectedDate = dateStr || '';
      },
    });
    if (this.initialDate) this.fp.setDate(this.initialDate);
  },
  submitForm(e) {
    // Form submits naturally with hidden inputs
  },
}));

function homeSlider(config) {
  const fixedSlideBy = config?.fixedSlideBy;
  return {
    slideBy: fixedSlideBy ?? config?.slideBy ?? 1,
    fixedSlideBy: fixedSlideBy != null,
    init() {
      if (!this.fixedSlideBy) {
        this.$nextTick(() => this.updateSlideBy());
        window.addEventListener('resize', () => this.updateSlideBy());
      }
    },
    updateSlideBy() {
      if (this.fixedSlideBy) return;
      const el = this.$refs.track;
      if (!el) return;
      const cards = el.querySelectorAll('[data-slider-card]');
      if (cards.length === 0) return;
      const cardWidth = cards[0].offsetWidth;
      const container = el.querySelector('[data-slider-gap]');
      const gap = container ? parseInt(container.dataset.sliderGap || '20', 10) : 20;
      const containerWidth = el.parentElement?.offsetWidth ?? el.offsetWidth;
      const visibleCount = Math.floor((containerWidth + gap) / (cardWidth + gap));
      this.slideBy = Math.max(1, visibleCount);
    },
    scrollNext() {
      const el = this.$refs.track;
      if (!el) return;
      const cards = el.querySelectorAll('[data-slider-card]');
      if (cards.length === 0) return;
      const cardWidth = cards[0].offsetWidth;
      const container = el.querySelector('[data-slider-gap]');
      const gap = container ? parseInt(container.dataset.sliderGap || '20', 10) : 20;
      const scrollAmount = (cardWidth + gap) * this.slideBy;
      el.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    },
  };
}
Alpine.data('homeSlider', homeSlider);
window.homeSlider = homeSlider;

Alpine.start();
