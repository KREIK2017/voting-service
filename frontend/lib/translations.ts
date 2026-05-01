export const translations = {
  uk: {
    nav: {
      home: 'Головна',
      dashboard: 'Панель',
      available_polls: 'Опитування',
      my_votes: 'Мої голоси',
      login: 'Увійти',
      register: 'Реєстрація',
      logout: 'Вийти'
    },
    dashboard: {
      welcome: 'Вітаємо,',
      subtext: 'Ось що відбувається з вашим акаунтом.',
      active_polls: 'Активні опитування',
      my_votes: 'Мої голоси',
      total_votes: 'Всього голосів',
      status: 'Статус',
      recent_polls: 'Останні опитування',
      view_all: 'Дивитись всі'
    },
    poll: {
      active: 'Активне',
      closed: 'Закрите',
      multiple: 'Множинний вибір',
      ends: 'Завершується',
      votes: 'голосів',
      vote_now: 'Голосувати',
      results: 'Результати'
    },
    home: {
      hero_title: 'Висловіть свою думку',
      hero_subtitle: 'Беріть участь у активних опитуваннях або створюйте власні, щоб отримати відгуки від спільноти.',
      get_started: 'Почати',
      view_polls: 'Активні опитування',
      featured: 'Популярні опитування'
    },
    polls: {
      title: 'Доступні опитування',
      subtitle: 'Переглядайте та беріть участь у активних опитуваннях.'
    },
    my_votes: {
      title: 'Моя участь',
      subtitle: 'Історія всіх опитувань, у яких ви брали участь.',
      voted_for: 'Проголосовано за'
    }
  },
  en: {
    nav: {
      home: 'Home',
      dashboard: 'Dashboard',
      available_polls: 'Polls',
      my_votes: 'My Votes',
      login: 'Login',
      register: 'Register',
      logout: 'Logout'
    },
    dashboard: {
      welcome: 'Welcome,',
      subtext: "Here's what's happening with your account.",
      active_polls: 'Active Polls',
      my_votes: 'My Votes',
      total_votes: 'Total Votes',
      status: 'Status',
      recent_polls: 'Recent Polls',
      view_all: 'View all'
    },
    poll: {
      active: 'Active',
      closed: 'Closed',
      multiple: 'Multiple Choice',
      ends: 'Ends',
      votes: 'votes cast',
      vote_now: 'Vote Now',
      results: 'Results'
    },
    home: {
      hero_title: 'Voice Your Opinion',
      hero_subtitle: 'Participate in active polls or create your own to gather feedback from the community.',
      get_started: 'Get Started',
      view_polls: 'View Active Polls',
      featured: 'Featured Polls'
    },
    polls: {
      title: 'Available Polls',
      subtitle: 'Browse and participate in active polls.'
    },
    my_votes: {
      title: 'My Participation',
      subtitle: "Historical record of all polls you've participated in.",
      voted_for: 'Voted for'
    }
  }
};

export type Locale = 'uk' | 'en';
export type TranslationType = typeof translations.uk;
