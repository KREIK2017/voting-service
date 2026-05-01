'use client';

import { useEffect, useState } from 'react';
import api from '@/lib/api';
import { Poll } from '@/lib/types';
import PollCard from '@/components/PollCard';
import Link from 'next/link';

import { useTranslation } from "@/components/LanguageContext";

export default function Home() {
  const [featuredPolls, setFeaturedPolls] = useState<Poll[]>([]);
  const [loading, setLoading] = useState(true);
  const { t } = useTranslation();

  useEffect(() => {
    const fetchPolls = async () => {
      try {
        const response = await api.get('/polls');
        setFeaturedPolls(response.data.data.slice(0, 3));
      } catch (error) {
        console.error('Failed to fetch polls', error);
      } finally {
        setLoading(false);
      }
    };
    fetchPolls();
  }, []);

  return (
    <div className="space-y-16 pb-12">
      {/* Hero Section */}
      <section className="relative py-20 px-8 rounded-[2.5rem] overflow-hidden bg-white border border-gray-100 shadow-sm">
        <div className="relative z-10 max-w-3xl mx-auto text-center space-y-8">
          <h1 className="text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-tight">
            {t.home.hero_title}
          </h1>
          <p className="text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto">
            {t.home.hero_subtitle}
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Link
              href="/register"
              className="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95"
            >
              {t.home.get_started}
            </Link>
            <Link
              href="/polls"
              className="px-8 py-4 bg-white text-gray-700 border-2 border-gray-100 rounded-2xl font-bold text-lg hover:border-indigo-100 hover:bg-indigo-50/30 transition-all"
            >
              {t.home.view_polls}
            </Link>
          </div>
        </div>
      </section>

      {/* Featured Polls */}
      <section>
        <div className="flex justify-between items-end mb-10">
          <div>
            <h2 className="text-3xl font-bold text-gray-900">{t.home.featured}</h2>
          </div>
          <Link href="/polls" className="text-indigo-600 hover:text-indigo-500 font-bold flex items-center group">
            {t.dashboard.view_all}
            <span className="ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
          </Link>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {[1, 2, 3].map((i) => (
              <div key={i} className="animate-pulse bg-gray-200 h-64 rounded-xl"></div>
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {featuredPolls.map((poll) => (
              <PollCard key={poll.id} poll={poll} />
            ))}
            {featuredPolls.length === 0 && (
              <p className="col-span-full text-center text-gray-500 py-12">
                No active polls found at the moment.
              </p>
            )}
          </div>
        )}
      </section>
    </div>
  );
}
