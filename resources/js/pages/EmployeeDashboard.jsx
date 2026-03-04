import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { useAuth } from '../context/AuthContext'

export default function EmployeeDashboard() {
    const { user, logout } = useAuth()
    const [data, setData] = useState(null)

    useEffect(() => {
        loadData()
    }, [])

    const loadData = () => {
        axios.get('/api/dashboard').then(res => setData(res.data))
    }

    const handleCheckIn = async () => {
        await axios.post('/api/attendance/checkin')
        loadData()
    }

    const handleCheckOut = async () => {
        await axios.post('/api/attendance/checkout')
        loadData()
    }

    if (!data) return <div className="flex items-center justify-center h-screen">Loading...</div>

    return (
        <div className="min-h-screen bg-gray-50">
            <nav className="bg-white border-b px-8 py-4 flex justify-between items-center">
                <h1 className="text-2xl font-semibold">
                    <i className="fas fa-user-clock text-green-600 mr-2"></i>Employee Portal
                </h1>
                <div className="flex items-center gap-4">
                    <div className="w-9 h-9 rounded-lg bg-green-600 flex items-center justify-center text-white font-medium text-sm">
                        {user.name[0]}
                    </div>
                    <span className="font-medium text-gray-600">{user.name}</span>
                    <button onClick={logout} className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm font-medium">
                        <i className="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </nav>

            <div className="max-w-6xl mx-auto p-8">
                <div className="bg-white border rounded-xl p-8 mb-8">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-2">Welcome, {user.name}!</h2>
                    <p>Today is {new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>

                <div className="grid grid-cols-2 gap-8 mb-8">
                    <div className="bg-white border rounded-xl p-6">
                        <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i className="fas fa-clock"></i> Attendance Today
                        </h3>
                        <div className="text-center py-8">
                            {data.todayAttendance ? (
                                data.todayAttendance.check_out ? (
                                    <>
                                        <div className="text-5xl text-green-600 mb-4"><i className="fas fa-check-circle"></i></div>
                                        <div className="text-lg font-semibold text-green-600 mb-2">Work Complete</div>
                                        <div className="text-gray-500">Check In: {data.todayAttendance.check_in}</div>
                                        <div className="text-gray-500">Check Out: {data.todayAttendance.check_out}</div>
                                    </>
                                ) : (
                                    <>
                                        <div className="text-5xl text-blue-600 mb-4"><i className="fas fa-play-circle"></i></div>
                                        <div className="text-lg font-semibold text-blue-600 mb-2">Working</div>
                                        <div className="text-gray-500 mb-4">Check In: {data.todayAttendance.check_in}</div>
                                        <button onClick={handleCheckOut} className="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 font-medium">
                                            <i className="fas fa-sign-out-alt"></i> Check Out
                                        </button>
                                    </>
                                )
                            ) : (
                                <>
                                    <div className="text-5xl text-gray-400 mb-4"><i className="fas fa-clock"></i></div>
                                    <div className="text-lg font-semibold text-gray-600 mb-4">Not Checked In</div>
                                    <button onClick={handleCheckIn} className="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium">
                                        <i className="fas fa-sign-in-alt"></i> Check In
                                    </button>
                                </>
                            )}
                        </div>
                    </div>

                    <div className="bg-white border rounded-xl p-6">
                        <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i className="fas fa-bullhorn"></i> Announcements
                        </h3>
                        {data.announcements?.length > 0 ? (
                            data.announcements.map(ann => (
                                <div key={ann.id} className="p-4 rounded-lg mb-3 bg-yellow-50 border border-yellow-200">
                                    <div className="font-semibold mb-1">{ann.title}</div>
                                    <div className="text-sm mb-2">{ann.content}</div>
                                    {ann.meeting_date && <div className="text-xs text-gray-600"><i className="fas fa-calendar"></i> {ann.meeting_date}</div>}
                                </div>
                            ))
                        ) : (
                            <p className="text-gray-500">No announcements at this time.</p>
                        )}
                    </div>
                </div>

                <div className="bg-white border rounded-xl p-6">
                    <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i className="fas fa-history"></i> Recent Attendance
                    </h3>
                    <div className="max-h-80 overflow-y-auto">
                        {data.recentAttendance?.length > 0 ? (
                            data.recentAttendance.map(att => (
                                <div key={att.id} className="flex justify-between py-3 border-b last:border-b-0">
                                    <div>
                                        <strong>{att.date}</strong>
                                        <span className="text-gray-500"> - {att.status}</span>
                                    </div>
                                    <div className="text-gray-600">
                                        {att.check_in && `In: ${att.check_in}`}
                                        {att.check_out && ` | Out: ${att.check_out}`}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p className="text-gray-500">No attendance records found.</p>
                        )}
                    </div>
                </div>
            </div>
        </div>
    )
}
