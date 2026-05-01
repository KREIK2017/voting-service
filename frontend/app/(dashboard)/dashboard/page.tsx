'use client';

import { useEffect, useState } from 'react';
import { getUser } from '@/lib/auth';
import { User, Poll } from '@/lib/types';
import api from '@/lib/api';
import PollCard from '@/components/PollCard';
import Link from 'next/link';
import { PlusCircle, BarChart, CheckCircle } from 'lucide-react';

import { useTranslation } from '@/components/LanguageContext';

export default function DashboardPage() {
  const [user, setUser] = useState<User | null>(null);
  const [stats, setStats] = useState({ activePolls: 0, totalVotes: 0, myVotes: 0 });
  const [recentPolls, setRecentPolls] = useState<Poll[]>([]);
  const [loading, setLoading] = useState(true);
  const { t } = useTranslation();

  useEffect(() => {
    const userData = getUser();
    setUser(userData);

    const fetchData = async () => {
      try {
        const pollsRes = await api.get('/polls');
        const allPolls = pollsRes.data.data;
        
        setRecentPolls(allPolls.slice(0, 3));
        
        if (userData?.role === 'voter') {
          const myVotesRes = await api.get('/my-votes');
          setStats({
            activePolls: allPolls.filter((p: Poll) => p.is_active).length,
            totalVotes: 0, // Placeholder
            myVotes: myVotesRes.data.data.length
          });
        } else {
          setStats({
            activePolls: allPolls.filter((p: Poll) => p.is_active).length,
            totalVotes: allPolls.reduce((acc: number, p: Poll) => acc + (p.votes_count || 0), 0),
            myVotes: 0
          });
        }
      } catch (error) {
        console.error('Failed to fetch dashboard data', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (!user) return null;

  return (
    <div className="space-y-8">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">{t.dashboard.welcome} {user.name}!</h1>
          <p className="text-gray-600">{t.dashboard.subtext}</p>
        </div>
        {user.role === 'admin' && (
          <Link
            href="/admin/polls/create"
            className="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
          >
            <PlusCircle className="w-5 h-5 mr-2" />
            Create Poll
          </Link>
        )}
      </div>

      <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div className="flex items-center text-indigo-600 mb-2">
            <BarChart className="w-5 h-5 mr-2" />
            <span className="text-sm font-semibold uppercase tracking-wider">{t.dashboard.active_polls}</span>
          </div>
          <p className="text-3xl font-bold text-gray-900">{stats.activePolls}</p>
        </div>
        
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          {user.role === 'admin' ? (
            <>
              <div className="flex items-center text-green-600 mb-2">
                <CheckCircle className="w-5 h-5 mr-2" />
                <span className="text-sm font-semibold uppercase tracking-wider">{t.dashboard.total_votes}</span>
              </div>
              <p className="text-3xl font-bold text-gray-900">{stats.totalVotes}</p>
            </>
          ) : (
            <>
              <div className="flex items-center text-blue-600 mb-2">
                <CheckCircle className="w-5 h-5 mr-2" />
                <span className="text-sm font-semibold uppercase tracking-wider">{t.dashboard.my_votes}</span>
              </div>
              <p className="text-3xl font-bold text-gray-900">{stats.myVotes}</p>
            </>
          )}
        </div>
        
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div className="flex items-center text-purple-600 mb-2">
            <PlusCircle className="w-5 h-5 mr-2" />
            <span className="text-sm font-semibold uppercase tracking-wider">{t.dashboard.status}</span>
          </div>
          <p className="text-3xl font-bold text-gray-900 capitalize">{user.role}</p>
        </div>
      </div>

      <div>
        <div className="flex justify-between items-center mb-6">
          <h2 className="text-2xl font-bold text-gray-900">{t.dashboard.recent_polls}</h2>
          <Link href={user.role === 'admin' ? '/admin/polls' : '/polls'} className="text-indigo-600 hover:text-indigo-500 font-medium">
            {t.dashboard.view_all} &rarr;
          </Link>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
            {[1, 2, 3].map((i) => (
              <div key={i} className="animate-pulse bg-gray-200 h-64 rounded-xl"></div>
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
            {recentPolls.map((poll) => (
              <PollCard key={poll.id} poll={poll} />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
