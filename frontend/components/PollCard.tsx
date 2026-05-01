import Link from 'next/link';
import { useTranslation } from '@/components/LanguageContext';
import { Poll } from '@/lib/types';
import { Calendar, Users } from 'lucide-react';

interface PollCardProps {
  poll: Poll;
}

export default function PollCard({ poll }: PollCardProps) {
  const { t } = useTranslation();

  return (
    <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition-shadow">
      <div className="flex justify-between items-start mb-4">
        <span className="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
          {t.poll.active}
        </span>
        {poll.allow_multiple && (
          <span className="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
            {t.poll.multiple}
          </span>
        )}
      </div>

      <h3 className="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{poll.title}</h3>
      <p className="text-gray-600 text-sm mb-6 line-clamp-3 min-h-[3rem]">
        {poll.description || 'No description provided.'}
      </p>

      <div className="mt-auto space-y-4">
        <div className="flex flex-col space-y-2 text-sm text-gray-500">
          <div className="flex items-center">
            <Calendar className="w-4 h-4 mr-2 text-gray-400" />
            <span>{t.poll.ends}: {poll.ends_at ? new Date(poll.ends_at).toLocaleDateString() : 'N/A'}</span>
          </div>
          <div className="flex items-center">
            <Users className="w-4 h-4 mr-2 text-gray-400" />
            <span>{poll.votes_count || 0} {t.poll.votes}</span>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-3 pt-4 border-t border-gray-50">
          <Link
            href={`/polls/${poll.id}`}
            className="w-full bg-indigo-600 text-white text-center py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-100"
          >
            {t.poll.vote_now}
          </Link>
          <Link
            href={`/polls/${poll.id}/results`}
            className="w-full bg-white text-gray-700 border border-gray-200 text-center py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-colors"
          >
            {t.poll.results}
          </Link>
        </div>
      </div>
    </div>
  );
}
