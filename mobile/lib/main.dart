import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import 'dart:convert';

import 'config/api_config.dart';

void main() {
  runApp(const FitLifeApp());
}

class FitLifeApp extends StatelessWidget {
  const FitLifeApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'FitLife Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        scaffoldBackgroundColor: const Color(0xFFF8F9FA),
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF00C853),
          primary: const Color(0xFF00C853),
        ),
        useMaterial3: true,
      ),
      home: const LoginScreen(),
    );
  }
}

// -----------------------------------------------------------------------------
// 1. LOGIN SCREEN
// -----------------------------------------------------------------------------
class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  Future<void> _login() async {
    setState(() => _isLoading = true);
    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/login.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': _emailController.text.trim(),
          'password': _passwordController.text.trim(),
        }),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        if (!mounted) return;
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => MainTabNavigation(userData: data['user']),
          ),
        );
      } else {
        _showError(data['message'] ?? 'Login failed');
      }
    } catch (e) {
      _showError('Connection error to server');
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _showError(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: .stretch,
            children: [
              const Icon(
                Icons.fitness_center,
                size: 64,
                color: Color(0xFF00C853),
              ),
              const SizedBox(height: 12),
              const Text(
                'FITLIFE',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                  letterSpacing: 1.5,
                ),
              ),
              const SizedBox(height: 32),
              TextField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: _passwordController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Password',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: _isLoading ? null : _login,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF00C853),
                  padding: const EdgeInsets.symmetric(vertical: 16),
                ),
                child: _isLoading
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text(
                        'LOG IN',
                        style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// -----------------------------------------------------------------------------
// MAIN TAB NAVIGATION
// -----------------------------------------------------------------------------
class MainTabNavigation extends StatefulWidget {
  final Map<String, dynamic> userData;
  const MainTabNavigation({super.key, required this.userData});

  @override
  State<MainTabNavigation> createState() => _MainTabNavigationState();
}

class _MainTabNavigationState extends State<MainTabNavigation> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    final screens = [
      HomeScreen(userData: widget.userData),
      WorkoutScreen(memberId: widget.userData['member_id'] ?? 1),
      AttendanceScreen(memberId: widget.userData['member_id'] ?? 1),
    ];

    return Scaffold(
      body: screens[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        selectedItemColor: const Color(0xFF00C853),
        onTap: (index) => setState(() => _currentIndex = index),
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(
            icon: Icon(Icons.fitness_center),
            label: 'Workouts',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.history),
            label: 'Attendance',
          ),
        ],
      ),
    );
  }
}

// -----------------------------------------------------------------------------
// 2. HOME SCREEN (MEMBER STATUS)
// -----------------------------------------------------------------------------
class HomeScreen extends StatelessWidget {
  final Map<String, dynamic> userData;
  const HomeScreen({super.key, required this.userData});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('FitLife Member Dashboard'),
        automaticallyImplyLeading: false,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: .start,
          children: [
            Card(
              color: Colors.white,
              elevation: 1,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Row(
                  children: [
                    const CircleAvatar(
                      radius: 28,
                      backgroundColor: Color(0xFFE8F5E9),
                      child: Icon(
                        Icons.person,
                        color: Color(0xFF00C853),
                        size: 32,
                      ),
                    ),
                    const SizedBox(width: 16),
                    Column(
                      crossAxisAlignment: .start,
                      children: [
                        Text(
                          userData['name'] ?? 'Member',
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 4,
                          ),
                          decoration: BoxDecoration(
                            color: const Color(0xFFE8F5E9),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Text(
                            'Active Membership',
                            style: TextStyle(
                              color: Color(0xFF00C853),
                              fontSize: 12,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// -----------------------------------------------------------------------------
// 3. WORKOUT SCREEN WITH ANIMATED GIF DEMONSTRATION
// -----------------------------------------------------------------------------
class WorkoutScreen extends StatelessWidget {
  final int memberId;
  const WorkoutScreen({super.key, required this.memberId});

  @override
  Widget build(BuildContext context) {
    // Sample static list mapping to local exercise GIF URLs
    final List<Map<String, String>> exercises = [
      {
        'title': 'Push-Ups',
        'sets_reps': '3 Sets × 12 Reps',
        'gif': '${ApiConfig.baseUrl}/uploads/pushup.gif',
      },
      {
        'title': 'Bodyweight Squats',
        'sets_reps': '4 Sets × 15 Reps',
        'gif': '${ApiConfig.baseUrl}/uploads/squat.gif',
      },
    ];

    return Scaffold(
      appBar: AppBar(title: const Text('Assigned Workouts')),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: exercises.length,
        itemBuilder: (context, index) {
          final item = exercises[index];
          return Card(
            color: Colors.white,
            margin: const EdgeInsets.all(16),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    item['title']!,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    item['sets_reps']!,
                    style: const TextStyle(
                      color: Color(0xFF00C853),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 12),
                  ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: Image.network(
                      item['gif']!,
                      height: 180,
                      width: double.infinity,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) => Container(
                        height: 180,
                        color: Colors.grey[200],
                        child: const Center(
                          child: Icon(
                            Icons.image,
                            size: 48,
                            color: Colors.grey,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

// -----------------------------------------------------------------------------
// 4. ATTENDANCE HISTORY SCREEN
// -----------------------------------------------------------------------------
class AttendanceScreen extends StatelessWidget {
  final int memberId;
  const AttendanceScreen({super.key, required this.memberId});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Attendance History')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: const [
          ListTile(
            leading: Icon(Icons.check_circle, color: Color(0xFF00C853)),
            title: Text('Gym Check-In Verified'),
            subtitle: Text('Today, 08:30 AM'),
          ),
          Divider(),
          ListTile(
            leading: Icon(Icons.check_circle, color: Color(0xFF00C853)),
            title: Text('Gym Check-In Verified'),
            subtitle: Text('Yesterday, 05:15 PM'),
          ),
        ],
      ),
    );
  }
}
