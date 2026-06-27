-- --------------------------------------------------------
-- Host:                         192.168.11.80
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dbs_actasys_inventro
DROP DATABASE IF EXISTS `dbs_actasys_inventro`;
CREATE DATABASE IF NOT EXISTS `dbs_actasys_inventro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `dbs_actasys_inventro`;

-- Dumping structure for table dbs_actasys_inventro.tb_act_acc
DROP TABLE IF EXISTS `tb_act_acc`;
CREATE TABLE IF NOT EXISTS `tb_act_acc` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='access';

-- Dumping data for table dbs_actasys_inventro.tb_act_acc: ~6 rows (approximately)
DELETE FROM `tb_act_acc`;
INSERT INTO `tb_act_acc` (`id`, `nama`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 'Personalia', 1, NULL, '2025-07-02 13:33:17', NULL, '2026-04-22 06:26:51'),
	(2, 'Payroll', 1, NULL, '2025-08-29 04:11:36', NULL, '2026-04-22 06:26:43'),
	(3, 'GA', 1, NULL, '2025-08-30 22:50:40', NULL, '2026-04-22 06:26:27'),
	(4, 'HRD', 1, NULL, '2025-04-11 10:28:59', NULL, '2026-04-22 06:26:35'),
	(11, 'Admin', 1, NULL, '2026-04-22 06:26:05', NULL, '2026-04-22 06:26:05'),
	(17, 'Admin2', 1, NULL, '2026-05-24 08:22:39', NULL, '2026-05-24 08:22:39');

-- Dumping structure for table dbs_actasys_inventro.tb_act_acc_oto
DROP TABLE IF EXISTS `tb_act_acc_oto`;
CREATE TABLE IF NOT EXISTS `tb_act_acc_oto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `access_id` bigint(20) DEFAULT NULL,
  `software_id` bigint(20) DEFAULT NULL,
  `module_id` bigint(20) DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `module_id` (`module_id`) USING BTREE,
  KEY `software_id` (`software_id`),
  KEY `level_id` (`access_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1986 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='otorisasi';

-- Dumping data for table dbs_actasys_inventro.tb_act_acc_oto: ~341 rows (approximately)
DELETE FROM `tb_act_acc_oto`;
INSERT INTO `tb_act_acc_oto` (`id`, `access_id`, `software_id`, `module_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1636, 3, 2, 26, NULL, NULL, NULL, NULL),
	(1637, 3, 2, 27, NULL, NULL, NULL, NULL),
	(1638, 3, 2, 28, NULL, NULL, NULL, NULL),
	(1639, 3, 2, 29, NULL, NULL, NULL, NULL),
	(1640, 3, 2, 30, NULL, NULL, NULL, NULL),
	(1641, 3, 2, 31, NULL, NULL, NULL, NULL),
	(1642, 3, 2, 32, NULL, NULL, NULL, NULL),
	(1643, 3, 2, 33, NULL, NULL, NULL, NULL),
	(1644, 3, 2, 34, NULL, NULL, NULL, NULL),
	(1645, 3, 2, 35, NULL, NULL, NULL, NULL),
	(1646, 3, 2, 36, NULL, NULL, NULL, NULL),
	(1647, 3, 2, 37, NULL, NULL, NULL, NULL),
	(1648, 3, 2, 38, NULL, NULL, NULL, NULL),
	(1649, 3, 2, 39, NULL, NULL, NULL, NULL),
	(1650, 3, 2, 40, NULL, NULL, NULL, NULL),
	(1651, 3, 2, 41, NULL, NULL, NULL, NULL),
	(1652, 3, 2, 42, NULL, NULL, NULL, NULL),
	(1653, 3, 2, 44, NULL, NULL, NULL, NULL),
	(1654, 1, 2, 26, NULL, NULL, NULL, NULL),
	(1655, 1, 2, 27, NULL, NULL, NULL, NULL),
	(1656, 1, 2, 28, NULL, NULL, NULL, NULL),
	(1657, 1, 2, 29, NULL, NULL, NULL, NULL),
	(1658, 1, 2, 30, NULL, NULL, NULL, NULL),
	(1659, 1, 2, 31, NULL, NULL, NULL, NULL),
	(1660, 1, 2, 32, NULL, NULL, NULL, NULL),
	(1661, 1, 2, 33, NULL, NULL, NULL, NULL),
	(1662, 1, 2, 34, NULL, NULL, NULL, NULL),
	(1663, 1, 2, 35, NULL, NULL, NULL, NULL),
	(1664, 1, 2, 36, NULL, NULL, NULL, NULL),
	(1665, 1, 2, 37, NULL, NULL, NULL, NULL),
	(1666, 1, 2, 38, NULL, NULL, NULL, NULL),
	(1667, 1, 2, 39, NULL, NULL, NULL, NULL),
	(1668, 1, 2, 40, NULL, NULL, NULL, NULL),
	(1669, 1, 2, 41, NULL, NULL, NULL, NULL),
	(1670, 1, 2, 42, NULL, NULL, NULL, NULL),
	(1671, 1, 2, 44, NULL, NULL, NULL, NULL),
	(1672, 2, 2, 26, NULL, NULL, NULL, NULL),
	(1673, 2, 2, 27, NULL, NULL, NULL, NULL),
	(1674, 2, 2, 28, NULL, NULL, NULL, NULL),
	(1675, 2, 2, 29, NULL, NULL, NULL, NULL),
	(1676, 2, 2, 30, NULL, NULL, NULL, NULL),
	(1677, 2, 2, 31, NULL, NULL, NULL, NULL),
	(1678, 2, 2, 32, NULL, NULL, NULL, NULL),
	(1679, 2, 2, 33, NULL, NULL, NULL, NULL),
	(1680, 2, 2, 34, NULL, NULL, NULL, NULL),
	(1681, 2, 2, 35, NULL, NULL, NULL, NULL),
	(1682, 2, 2, 36, NULL, NULL, NULL, NULL),
	(1683, 2, 2, 37, NULL, NULL, NULL, NULL),
	(1684, 2, 2, 38, NULL, NULL, NULL, NULL),
	(1685, 2, 2, 39, NULL, NULL, NULL, NULL),
	(1686, 2, 2, 40, NULL, NULL, NULL, NULL),
	(1687, 2, 2, 41, NULL, NULL, NULL, NULL),
	(1688, 2, 2, 42, NULL, NULL, NULL, NULL),
	(1689, 2, 2, 44, NULL, NULL, NULL, NULL),
	(1690, 4, 2, 26, NULL, NULL, NULL, NULL),
	(1691, 4, 2, 27, NULL, NULL, NULL, NULL),
	(1692, 4, 2, 28, NULL, NULL, NULL, NULL),
	(1693, 4, 2, 29, NULL, NULL, NULL, NULL),
	(1694, 4, 2, 30, NULL, NULL, NULL, NULL),
	(1695, 4, 2, 31, NULL, NULL, NULL, NULL),
	(1696, 4, 2, 32, NULL, NULL, NULL, NULL),
	(1697, 4, 2, 33, NULL, NULL, NULL, NULL),
	(1698, 4, 2, 34, NULL, NULL, NULL, NULL),
	(1699, 4, 2, 35, NULL, NULL, NULL, NULL),
	(1700, 4, 2, 36, NULL, NULL, NULL, NULL),
	(1701, 4, 2, 37, NULL, NULL, NULL, NULL),
	(1702, 4, 2, 38, NULL, NULL, NULL, NULL),
	(1703, 4, 2, 39, NULL, NULL, NULL, NULL),
	(1704, 4, 2, 40, NULL, NULL, NULL, NULL),
	(1705, 4, 2, 41, NULL, NULL, NULL, NULL),
	(1706, 4, 2, 42, NULL, NULL, NULL, NULL),
	(1707, 4, 2, 44, NULL, NULL, NULL, NULL),
	(1708, 5, 2, 26, NULL, NULL, NULL, NULL),
	(1709, 5, 2, 27, NULL, NULL, NULL, NULL),
	(1710, 5, 2, 28, NULL, NULL, NULL, NULL),
	(1711, 5, 2, 29, NULL, NULL, NULL, NULL),
	(1712, 5, 2, 30, NULL, NULL, NULL, NULL),
	(1713, 5, 2, 31, NULL, NULL, NULL, NULL),
	(1714, 5, 2, 32, NULL, NULL, NULL, NULL),
	(1715, 5, 2, 33, NULL, NULL, NULL, NULL),
	(1716, 5, 2, 34, NULL, NULL, NULL, NULL),
	(1717, 5, 2, 35, NULL, NULL, NULL, NULL),
	(1718, 5, 2, 36, NULL, NULL, NULL, NULL),
	(1719, 5, 2, 37, NULL, NULL, NULL, NULL),
	(1720, 5, 2, 38, NULL, NULL, NULL, NULL),
	(1721, 5, 2, 39, NULL, NULL, NULL, NULL),
	(1722, 5, 2, 40, NULL, NULL, NULL, NULL),
	(1723, 5, 2, 41, NULL, NULL, NULL, NULL),
	(1724, 5, 2, 42, NULL, NULL, NULL, NULL),
	(1725, 5, 2, 44, NULL, NULL, NULL, NULL),
	(1726, 3, 2, 5, NULL, NULL, NULL, NULL),
	(1727, 3, 2, 6, NULL, NULL, NULL, NULL),
	(1728, 3, 2, 7, NULL, NULL, NULL, NULL),
	(1729, 1, 2, 5, NULL, NULL, NULL, NULL),
	(1730, 1, 2, 6, NULL, NULL, NULL, NULL),
	(1731, 1, 2, 7, NULL, NULL, NULL, NULL),
	(1732, 2, 2, 5, NULL, NULL, NULL, NULL),
	(1733, 2, 2, 6, NULL, NULL, NULL, NULL),
	(1734, 2, 2, 7, NULL, NULL, NULL, NULL),
	(1735, 4, 2, 5, NULL, NULL, NULL, NULL),
	(1736, 4, 2, 6, NULL, NULL, NULL, NULL),
	(1737, 4, 2, 7, NULL, NULL, NULL, NULL),
	(1738, 5, 2, 5, NULL, NULL, NULL, NULL),
	(1739, 5, 2, 6, NULL, NULL, NULL, NULL),
	(1740, 5, 2, 7, NULL, NULL, NULL, NULL),
	(1741, 3, 2, 3, NULL, NULL, NULL, NULL),
	(1742, 3, 2, 4, NULL, NULL, NULL, NULL),
	(1743, 3, 2, 8, NULL, NULL, NULL, NULL),
	(1744, 3, 2, 9, NULL, NULL, NULL, NULL),
	(1745, 3, 2, 10, NULL, NULL, NULL, NULL),
	(1746, 3, 2, 11, NULL, NULL, NULL, NULL),
	(1747, 3, 2, 43, NULL, NULL, NULL, NULL),
	(1748, 1, 2, 3, NULL, NULL, NULL, NULL),
	(1749, 1, 2, 4, NULL, NULL, NULL, NULL),
	(1750, 1, 2, 8, NULL, NULL, NULL, NULL),
	(1751, 1, 2, 9, NULL, NULL, NULL, NULL),
	(1752, 1, 2, 10, NULL, NULL, NULL, NULL),
	(1753, 1, 2, 11, NULL, NULL, NULL, NULL),
	(1754, 1, 2, 43, NULL, NULL, NULL, NULL),
	(1755, 2, 2, 3, NULL, NULL, NULL, NULL),
	(1756, 2, 2, 4, NULL, NULL, NULL, NULL),
	(1757, 2, 2, 8, NULL, NULL, NULL, NULL),
	(1758, 2, 2, 9, NULL, NULL, NULL, NULL),
	(1759, 2, 2, 10, NULL, NULL, NULL, NULL),
	(1760, 2, 2, 11, NULL, NULL, NULL, NULL),
	(1761, 2, 2, 43, NULL, NULL, NULL, NULL),
	(1762, 4, 2, 3, NULL, NULL, NULL, NULL),
	(1763, 4, 2, 4, NULL, NULL, NULL, NULL),
	(1764, 4, 2, 8, NULL, NULL, NULL, NULL),
	(1765, 4, 2, 9, NULL, NULL, NULL, NULL),
	(1766, 4, 2, 10, NULL, NULL, NULL, NULL),
	(1767, 4, 2, 11, NULL, NULL, NULL, NULL),
	(1768, 4, 2, 43, NULL, NULL, NULL, NULL),
	(1769, 5, 2, 3, NULL, NULL, NULL, NULL),
	(1770, 5, 2, 4, NULL, NULL, NULL, NULL),
	(1771, 5, 2, 8, NULL, NULL, NULL, NULL),
	(1772, 5, 2, 9, NULL, NULL, NULL, NULL),
	(1773, 5, 2, 10, NULL, NULL, NULL, NULL),
	(1774, 5, 2, 11, NULL, NULL, NULL, NULL),
	(1775, 5, 2, 43, NULL, NULL, NULL, NULL),
	(1776, 3, 3, 1, NULL, NULL, NULL, NULL),
	(1777, 3, 3, 2, NULL, NULL, NULL, NULL),
	(1778, 3, 3, 12, NULL, NULL, NULL, NULL),
	(1779, 3, 3, 13, NULL, NULL, NULL, NULL),
	(1780, 3, 3, 14, NULL, NULL, NULL, NULL),
	(1781, 3, 3, 15, NULL, NULL, NULL, NULL),
	(1782, 3, 3, 16, NULL, NULL, NULL, NULL),
	(1783, 3, 3, 17, NULL, NULL, NULL, NULL),
	(1784, 3, 3, 18, NULL, NULL, NULL, NULL),
	(1785, 3, 3, 19, NULL, NULL, NULL, NULL),
	(1786, 3, 3, 20, NULL, NULL, NULL, NULL),
	(1787, 3, 3, 21, NULL, NULL, NULL, NULL),
	(1788, 3, 3, 22, NULL, NULL, NULL, NULL),
	(1789, 3, 3, 23, NULL, NULL, NULL, NULL),
	(1790, 3, 3, 24, NULL, NULL, NULL, NULL),
	(1791, 3, 3, 25, NULL, NULL, NULL, NULL),
	(1792, 1, 3, 1, NULL, NULL, NULL, NULL),
	(1793, 1, 3, 2, NULL, NULL, NULL, NULL),
	(1794, 1, 3, 12, NULL, NULL, NULL, NULL),
	(1795, 1, 3, 13, NULL, NULL, NULL, NULL),
	(1796, 1, 3, 14, NULL, NULL, NULL, NULL),
	(1797, 1, 3, 15, NULL, NULL, NULL, NULL),
	(1798, 1, 3, 16, NULL, NULL, NULL, NULL),
	(1799, 1, 3, 17, NULL, NULL, NULL, NULL),
	(1800, 1, 3, 18, NULL, NULL, NULL, NULL),
	(1801, 1, 3, 19, NULL, NULL, NULL, NULL),
	(1802, 1, 3, 20, NULL, NULL, NULL, NULL),
	(1803, 1, 3, 21, NULL, NULL, NULL, NULL),
	(1804, 1, 3, 22, NULL, NULL, NULL, NULL),
	(1805, 1, 3, 23, NULL, NULL, NULL, NULL),
	(1806, 1, 3, 24, NULL, NULL, NULL, NULL),
	(1807, 1, 3, 25, NULL, NULL, NULL, NULL),
	(1808, 2, 3, 1, NULL, NULL, NULL, NULL),
	(1809, 2, 3, 2, NULL, NULL, NULL, NULL),
	(1810, 2, 3, 12, NULL, NULL, NULL, NULL),
	(1811, 2, 3, 13, NULL, NULL, NULL, NULL),
	(1812, 2, 3, 14, NULL, NULL, NULL, NULL),
	(1813, 2, 3, 15, NULL, NULL, NULL, NULL),
	(1814, 2, 3, 16, NULL, NULL, NULL, NULL),
	(1815, 2, 3, 17, NULL, NULL, NULL, NULL),
	(1816, 2, 3, 18, NULL, NULL, NULL, NULL),
	(1817, 2, 3, 19, NULL, NULL, NULL, NULL),
	(1818, 2, 3, 20, NULL, NULL, NULL, NULL),
	(1819, 2, 3, 21, NULL, NULL, NULL, NULL),
	(1820, 2, 3, 22, NULL, NULL, NULL, NULL),
	(1821, 2, 3, 23, NULL, NULL, NULL, NULL),
	(1822, 2, 3, 24, NULL, NULL, NULL, NULL),
	(1823, 2, 3, 25, NULL, NULL, NULL, NULL),
	(1824, 4, 3, 1, NULL, NULL, NULL, NULL),
	(1825, 4, 3, 2, NULL, NULL, NULL, NULL),
	(1826, 4, 3, 12, NULL, NULL, NULL, NULL),
	(1827, 4, 3, 13, NULL, NULL, NULL, NULL),
	(1828, 4, 3, 14, NULL, NULL, NULL, NULL),
	(1829, 4, 3, 15, NULL, NULL, NULL, NULL),
	(1830, 4, 3, 16, NULL, NULL, NULL, NULL),
	(1831, 4, 3, 17, NULL, NULL, NULL, NULL),
	(1832, 4, 3, 18, NULL, NULL, NULL, NULL),
	(1833, 4, 3, 19, NULL, NULL, NULL, NULL),
	(1834, 4, 3, 20, NULL, NULL, NULL, NULL),
	(1835, 4, 3, 21, NULL, NULL, NULL, NULL),
	(1836, 4, 3, 22, NULL, NULL, NULL, NULL),
	(1837, 4, 3, 23, NULL, NULL, NULL, NULL),
	(1838, 4, 3, 24, NULL, NULL, NULL, NULL),
	(1839, 4, 3, 25, NULL, NULL, NULL, NULL),
	(1840, 5, 3, 1, NULL, NULL, NULL, NULL),
	(1841, 5, 3, 2, NULL, NULL, NULL, NULL),
	(1842, 5, 3, 12, NULL, NULL, NULL, NULL),
	(1843, 5, 3, 13, NULL, NULL, NULL, NULL),
	(1844, 5, 3, 14, NULL, NULL, NULL, NULL),
	(1845, 5, 3, 15, NULL, NULL, NULL, NULL),
	(1846, 5, 3, 16, NULL, NULL, NULL, NULL),
	(1847, 5, 3, 17, NULL, NULL, NULL, NULL),
	(1848, 5, 3, 18, NULL, NULL, NULL, NULL),
	(1849, 5, 3, 19, NULL, NULL, NULL, NULL),
	(1850, 5, 3, 20, NULL, NULL, NULL, NULL),
	(1851, 5, 3, 21, NULL, NULL, NULL, NULL),
	(1852, 5, 3, 22, NULL, NULL, NULL, NULL),
	(1853, 5, 3, 23, NULL, NULL, NULL, NULL),
	(1854, 5, 3, 24, NULL, NULL, NULL, NULL),
	(1855, 5, 3, 25, NULL, NULL, NULL, NULL),
	(1859, NULL, NULL, 27, NULL, '2026-04-13 11:47:14', NULL, '2026-04-13 04:47:14'),
	(1860, NULL, NULL, 26, NULL, '2026-04-13 11:47:35', NULL, '2026-04-13 04:47:35'),
	(1862, 5, NULL, 50, NULL, '2026-04-15 05:14:32', NULL, '2026-04-14 22:14:32'),
	(1863, 5, NULL, 51, NULL, '2026-04-17 09:58:51', NULL, '2026-04-17 02:58:51'),
	(1864, 5, NULL, 52, NULL, '2026-04-17 09:58:52', NULL, '2026-04-17 02:58:52'),
	(1865, 5, NULL, 53, NULL, '2026-04-17 09:58:53', NULL, '2026-04-17 02:58:53'),
	(1866, 5, NULL, 54, NULL, '2026-04-17 09:58:54', NULL, '2026-04-17 02:58:54'),
	(1867, 5, NULL, 55, NULL, '2026-04-20 05:36:03', NULL, '2026-04-19 22:36:03'),
	(1868, 9, NULL, 79, NULL, '2026-04-21 01:57:09', NULL, '2026-04-20 18:57:09'),
	(1869, 9, NULL, 80, NULL, '2026-04-21 01:57:10', NULL, '2026-04-20 18:57:10'),
	(1870, 9, NULL, 73, NULL, '2026-04-21 01:57:15', NULL, '2026-04-20 18:57:15'),
	(1871, 9, NULL, 72, NULL, '2026-04-21 01:57:17', NULL, '2026-04-20 18:57:17'),
	(1872, 9, NULL, 74, NULL, '2026-04-21 01:57:18', NULL, '2026-04-20 18:57:18'),
	(1873, 9, NULL, 75, NULL, '2026-04-21 01:57:19', NULL, '2026-04-20 18:57:19'),
	(1874, 9, NULL, 76, NULL, '2026-04-21 01:57:20', NULL, '2026-04-20 18:57:20'),
	(1875, 9, NULL, 77, NULL, '2026-04-21 01:57:21', NULL, '2026-04-20 18:57:21'),
	(1876, 9, NULL, 78, NULL, '2026-04-21 01:57:22', NULL, '2026-04-20 18:57:22'),
	(1877, 9, NULL, 45, NULL, '2026-04-21 01:57:28', NULL, '2026-04-20 18:57:28'),
	(1878, 9, NULL, 46, NULL, '2026-04-21 01:57:29', NULL, '2026-04-20 18:57:29'),
	(1879, 9, NULL, 47, NULL, '2026-04-21 01:57:30', NULL, '2026-04-20 18:57:30'),
	(1880, 9, NULL, 49, NULL, '2026-04-21 01:57:31', NULL, '2026-04-20 18:57:31'),
	(1881, 9, NULL, 48, NULL, '2026-04-21 01:57:32', NULL, '2026-04-20 18:57:32'),
	(1882, 9, NULL, 58, NULL, '2026-04-21 01:57:33', NULL, '2026-04-20 18:57:33'),
	(1883, 9, NULL, 59, NULL, '2026-04-21 01:57:35', NULL, '2026-04-20 18:57:35'),
	(1884, 9, NULL, 60, NULL, '2026-04-21 01:57:36', NULL, '2026-04-20 18:57:36'),
	(1885, 9, NULL, 61, NULL, '2026-04-21 01:57:37', NULL, '2026-04-20 18:57:37'),
	(1886, 9, NULL, 62, NULL, '2026-04-21 01:57:38', NULL, '2026-04-20 18:57:38'),
	(1887, 9, NULL, 63, NULL, '2026-04-21 01:57:39', NULL, '2026-04-20 18:57:39'),
	(1888, 9, NULL, 64, NULL, '2026-04-21 01:57:42', NULL, '2026-04-20 18:57:42'),
	(1889, 9, NULL, 65, NULL, '2026-04-21 01:57:44', NULL, '2026-04-20 18:57:44'),
	(1890, 9, NULL, 66, NULL, '2026-04-21 01:57:45', NULL, '2026-04-20 18:57:45'),
	(1891, 9, NULL, 85, NULL, '2026-04-21 01:57:51', NULL, '2026-04-20 18:57:51'),
	(1892, 9, NULL, 86, NULL, '2026-04-21 01:57:53', NULL, '2026-04-20 18:57:53'),
	(1893, 9, NULL, 67, NULL, '2026-04-21 01:57:57', NULL, '2026-04-20 18:57:57'),
	(1894, 9, NULL, 68, NULL, '2026-04-21 01:57:58', NULL, '2026-04-20 18:57:58'),
	(1895, 9, NULL, 69, NULL, '2026-04-21 01:58:00', NULL, '2026-04-20 18:58:00'),
	(1896, 9, NULL, 70, NULL, '2026-04-21 01:58:01', NULL, '2026-04-20 18:58:01'),
	(1897, 9, NULL, 71, NULL, '2026-04-21 01:58:03', NULL, '2026-04-20 18:58:03'),
	(1898, 9, NULL, 81, NULL, '2026-04-21 01:58:07', NULL, '2026-04-20 18:58:07'),
	(1899, 9, NULL, 82, NULL, '2026-04-21 01:58:08', NULL, '2026-04-20 18:58:08'),
	(1900, 9, NULL, 83, NULL, '2026-04-21 01:58:11', NULL, '2026-04-20 18:58:11'),
	(1901, 9, NULL, 84, NULL, '2026-04-21 01:58:17', NULL, '2026-04-20 18:58:17'),
	(1902, 9, NULL, 56, NULL, '2026-04-21 01:58:21', NULL, '2026-04-20 18:58:21'),
	(1903, 9, NULL, 57, NULL, '2026-04-21 01:58:22', NULL, '2026-04-20 18:58:22'),
	(1904, 11, NULL, 88, NULL, '2026-04-22 06:34:11', NULL, '2026-04-21 23:34:11'),
	(1905, 11, NULL, 89, NULL, '2026-04-22 06:34:12', NULL, '2026-04-21 23:34:12'),
	(1906, 11, NULL, 90, NULL, '2026-04-22 06:34:13', NULL, '2026-04-21 23:34:13'),
	(1907, 11, NULL, 91, NULL, '2026-04-22 06:34:17', NULL, '2026-04-21 23:34:17'),
	(1908, 11, NULL, 87, NULL, '2026-04-22 06:34:21', NULL, '2026-04-21 23:34:21'),
	(1909, 11, NULL, 92, NULL, '2026-04-22 10:21:13', NULL, '2026-04-22 03:21:13'),
	(1910, 11, NULL, 93, NULL, '2026-04-22 10:21:15', NULL, '2026-04-22 03:21:15'),
	(1911, 11, NULL, 94, NULL, '2026-04-22 10:21:15', NULL, '2026-04-22 03:21:15'),
	(1912, 11, NULL, 95, NULL, '2026-04-22 10:21:16', NULL, '2026-04-22 03:21:16'),
	(1913, 11, NULL, 96, NULL, '2026-04-22 10:21:16', NULL, '2026-04-22 03:21:16'),
	(1914, 11, NULL, 97, NULL, '2026-04-22 10:22:18', NULL, '2026-04-22 03:22:18'),
	(1915, 9, NULL, 98, NULL, '2026-04-24 08:29:04', NULL, '2026-04-24 01:29:04'),
	(1916, 9, NULL, 99, NULL, '2026-04-24 08:29:04', NULL, '2026-04-24 01:29:04'),
	(1917, 9, NULL, 100, NULL, '2026-04-24 08:29:05', NULL, '2026-04-24 01:29:05'),
	(1918, 12, NULL, 101, NULL, '2026-04-27 09:24:35', NULL, '2026-04-27 02:24:35'),
	(1919, 12, NULL, 102, NULL, '2026-04-27 09:43:28', NULL, '2026-04-27 02:43:28'),
	(1920, 12, NULL, 103, NULL, '2026-04-27 09:43:29', NULL, '2026-04-27 02:43:29'),
	(1921, 12, NULL, 104, NULL, '2026-04-27 09:43:29', NULL, '2026-04-27 02:43:29'),
	(1922, 12, NULL, 105, NULL, '2026-04-27 09:43:31', NULL, '2026-04-27 02:43:31'),
	(1923, 11, NULL, 106, NULL, '2026-04-29 06:04:13', NULL, '2026-04-28 23:04:13'),
	(1924, 11, NULL, 107, NULL, '2026-04-29 08:37:39', NULL, '2026-04-29 01:37:39'),
	(1925, 11, NULL, 108, NULL, '2026-04-29 08:47:56', NULL, '2026-04-29 01:47:56'),
	(1926, 12, NULL, 110, NULL, '2026-05-03 06:31:06', NULL, '2026-05-02 23:31:06'),
	(1927, 12, NULL, 109, NULL, '2026-05-03 06:31:09', NULL, '2026-05-02 23:31:09'),
	(1928, 12, NULL, 111, NULL, '2026-05-03 06:31:12', NULL, '2026-05-02 23:31:12'),
	(1929, 12, NULL, 112, NULL, '2026-05-03 06:31:13', NULL, '2026-05-02 23:31:13'),
	(1930, 12, NULL, 113, NULL, '2026-05-03 06:31:14', NULL, '2026-05-02 23:31:14'),
	(1931, 14, NULL, 110, NULL, '2026-05-03 06:31:46', NULL, '2026-05-02 23:31:46'),
	(1932, 14, NULL, 109, NULL, '2026-05-03 06:31:48', NULL, '2026-05-02 23:31:48'),
	(1933, 14, NULL, 111, NULL, '2026-05-03 06:31:51', NULL, '2026-05-02 23:31:51'),
	(1936, 15, NULL, 110, NULL, '2026-05-03 06:33:47', NULL, '2026-05-02 23:33:47'),
	(1937, 15, NULL, 109, NULL, '2026-05-03 06:33:50', NULL, '2026-05-02 23:33:50'),
	(1938, 15, NULL, 111, NULL, '2026-05-03 06:33:52', NULL, '2026-05-02 23:33:52'),
	(1939, 15, NULL, 112, NULL, '2026-05-03 06:33:53', NULL, '2026-05-02 23:33:53'),
	(1940, 15, NULL, 113, NULL, '2026-05-03 06:33:53', NULL, '2026-05-02 23:33:53'),
	(1941, 16, NULL, 110, NULL, '2026-05-03 06:34:16', NULL, '2026-05-02 23:34:16'),
	(1942, 16, NULL, 109, NULL, '2026-05-03 06:34:19', NULL, '2026-05-02 23:34:19'),
	(1943, 16, NULL, 111, NULL, '2026-05-03 06:34:22', NULL, '2026-05-02 23:34:22'),
	(1944, 16, NULL, 112, NULL, '2026-05-03 06:34:22', NULL, '2026-05-02 23:34:22'),
	(1945, 16, NULL, 113, NULL, '2026-05-03 06:34:22', NULL, '2026-05-02 23:34:22'),
	(1946, 13, NULL, 114, NULL, '2026-05-05 06:29:16', NULL, '2026-05-04 23:29:16'),
	(1947, 13, NULL, 115, NULL, '2026-05-05 06:29:18', NULL, '2026-05-04 23:29:18'),
	(1948, 13, NULL, 116, NULL, '2026-05-05 06:29:19', NULL, '2026-05-04 23:29:19'),
	(1949, 13, NULL, 117, NULL, '2026-05-05 06:29:21', NULL, '2026-05-04 23:29:21'),
	(1950, 15, NULL, 118, NULL, '2026-05-05 08:51:28', NULL, '2026-05-05 01:51:28'),
	(1951, 15, NULL, 119, NULL, '2026-05-05 08:51:30', NULL, '2026-05-05 01:51:30'),
	(1952, 15, NULL, 120, NULL, '2026-05-06 14:08:39', NULL, '2026-05-06 07:08:39'),
	(1953, 11, NULL, 124, NULL, '2026-05-12 15:05:59', NULL, '2026-05-12 08:05:59'),
	(1954, 11, NULL, 121, NULL, '2026-05-12 15:06:06', NULL, '2026-05-12 08:06:06'),
	(1955, 11, NULL, 122, NULL, '2026-05-12 15:06:06', NULL, '2026-05-12 08:06:06'),
	(1956, 11, NULL, 123, NULL, '2026-05-12 15:06:08', NULL, '2026-05-12 08:06:08'),
	(1957, 9, NULL, 125, NULL, '2026-05-13 09:51:01', NULL, '2026-05-13 02:51:01'),
	(1958, 9, NULL, 126, NULL, '2026-05-13 09:51:01', NULL, '2026-05-13 02:51:01'),
	(1959, 9, NULL, 127, NULL, '2026-05-13 09:51:03', NULL, '2026-05-13 02:51:03'),
	(1960, 11, NULL, 128, NULL, '2026-05-18 14:21:42', NULL, '2026-05-18 07:21:42'),
	(1964, 11, 5, 129, NULL, '2026-05-22 05:51:58', NULL, '2026-05-21 22:51:58'),
	(1965, 11, NULL, 130, NULL, '2026-05-22 15:59:54', NULL, '2026-05-22 08:59:54'),
	(1966, 11, NULL, 131, NULL, '2026-05-22 15:59:54', NULL, '2026-05-22 08:59:54'),
	(1967, 11, NULL, 132, NULL, '2026-05-22 16:03:33', NULL, '2026-05-22 09:03:33'),
	(1968, 11, NULL, 133, NULL, '2026-05-23 12:05:00', NULL, '2026-05-23 05:05:00'),
	(1969, 11, NULL, 134, NULL, '2026-05-23 12:05:01', NULL, '2026-05-23 05:05:01'),
	(1970, 11, NULL, 135, NULL, '2026-05-23 12:05:02', NULL, '2026-05-23 05:05:02'),
	(1971, 11, NULL, 136, NULL, '2026-05-23 12:05:02', NULL, '2026-05-23 05:05:02'),
	(1972, 11, NULL, 137, NULL, '2026-05-23 12:05:03', NULL, '2026-05-23 05:05:03'),
	(1973, 11, NULL, 138, NULL, '2026-05-23 12:05:04', NULL, '2026-05-23 05:05:04'),
	(1974, 11, NULL, 139, NULL, '2026-05-23 12:05:04', NULL, '2026-05-23 05:05:04'),
	(1975, 17, NULL, 107, NULL, '2026-05-24 08:22:49', NULL, '2026-05-24 01:22:49'),
	(1976, 17, NULL, 108, NULL, '2026-05-24 08:22:51', NULL, '2026-05-24 01:22:51'),
	(1977, 17, NULL, 97, NULL, '2026-05-24 08:22:53', NULL, '2026-05-24 01:22:53'),
	(1978, 17, NULL, 91, NULL, '2026-05-24 08:22:54', NULL, '2026-05-24 01:22:54'),
	(1979, 17, NULL, 130, NULL, '2026-05-24 08:22:57', NULL, '2026-05-24 01:22:57'),
	(1980, 17, NULL, 131, NULL, '2026-05-24 08:22:57', NULL, '2026-05-24 01:22:57'),
	(1981, 17, NULL, 132, NULL, '2026-05-24 08:22:57', NULL, '2026-05-24 01:22:57'),
	(1982, 17, NULL, 88, NULL, '2026-05-24 08:23:00', NULL, '2026-05-24 01:23:00'),
	(1983, 17, NULL, 90, NULL, '2026-05-24 08:23:01', NULL, '2026-05-24 01:23:01'),
	(1984, 17, NULL, 94, NULL, '2026-05-24 08:23:02', NULL, '2026-05-24 01:23:02'),
	(1985, 17, NULL, 129, NULL, '2026-05-24 08:23:02', NULL, '2026-05-24 01:23:02');

-- Dumping structure for table dbs_actasys_inventro.tb_act_brc
DROP TABLE IF EXISTS `tb_act_brc`;
CREATE TABLE IF NOT EXISTS `tb_act_brc` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0 = non aktif, 1 = aktif',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='cabang';

-- Dumping data for table dbs_actasys_inventro.tb_act_brc: ~2 rows (approximately)
DELETE FROM `tb_act_brc`;
INSERT INTO `tb_act_brc` (`id`, `company_id`, `nama`, `kode`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 2, 'POLAMAS', 'PLM', 1, 'system', '2025-04-11 03:17:13', NULL, '2026-05-15 05:55:09'),
	(2, 2, 'SURITEX', 'SRT', 1, NULL, '2026-01-25 10:51:38', NULL, '2026-05-15 05:54:53');

-- Dumping structure for table dbs_actasys_inventro.tb_act_cpy
DROP TABLE IF EXISTS `tb_act_cpy`;
CREATE TABLE IF NOT EXISTS `tb_act_cpy` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_formal` varchar(100) DEFAULT NULL,
  `bidang_usaha` varchar(100) DEFAULT NULL,
  `npwp` varchar(100) DEFAULT NULL,
  `kode` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maps` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='company';

-- Dumping data for table dbs_actasys_inventro.tb_act_cpy: ~0 rows (approximately)
DELETE FROM `tb_act_cpy`;
INSERT INTO `tb_act_cpy` (`id`, `nama`, `nama_formal`, `bidang_usaha`, `npwp`, `kode`, `alamat`, `telepon`, `handphone`, `email`, `website`, `maps`, `instagram`, `logo`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(2, 'Suritex', 'CV. Suri Textile', NULL, NULL, 'SRT', 'Jl. Pahlawan Desa No.132, Utama, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40153', '022 6677000', '-', '-', '-', NULL, NULL, '2_1780178781.png', 1, NULL, '2025-09-14 06:21:21', NULL, '2026-05-30 22:06:21');

-- Dumping structure for table dbs_actasys_inventro.tb_act_sfr
DROP TABLE IF EXISTS `tb_act_sfr`;
CREATE TABLE IF NOT EXISTS `tb_act_sfr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `versi` decimal(5,2) DEFAULT 1.00,
  `show_footer` tinyint(4) DEFAULT 1,
  `copyright` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `developer` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `handphone1` varchar(100) DEFAULT NULL,
  `handphone2` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='software';

-- Dumping data for table dbs_actasys_inventro.tb_act_sfr: ~0 rows (approximately)
DELETE FROM `tb_act_sfr`;
INSERT INTO `tb_act_sfr` (`id`, `nama`, `kode`, `tagline`, `versi`, `show_footer`, `copyright`, `developer`, `website`, `email`, `handphone1`, `handphone2`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(5, 'Inventro', 'INV-001', 'Management Information System', 1.00, 1, 'Â© 2003 - 2026', 'PT. ACTASYS TEKNOLOGI INDONESIA', 'http://actasys.co.id', 'actasysteknologiindonesia@gmail.com', '+628211800051', '+628211800052', 1, NULL, '2026-04-15 02:44:08', NULL, '2026-06-09 02:54:36');

-- Dumping structure for table dbs_actasys_inventro.tb_act_sfr_tab
DROP TABLE IF EXISTS `tb_act_sfr_tab`;
CREATE TABLE IF NOT EXISTS `tb_act_sfr_tab` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `software_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `software_id` (`software_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='tab';

-- Dumping data for table dbs_actasys_inventro.tb_act_sfr_tab: ~27 rows (approximately)
DELETE FROM `tb_act_sfr_tab`;
INSERT INTO `tb_act_sfr_tab` (`id`, `software_id`, `nama`, `icon`, `urutan`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 2, 'General', 'bi bi-asterisk', 2, 1, NULL, '2025-06-29 21:51:01', NULL, '2025-06-29 21:51:01'),
	(2, 2, 'System', 'bi  bi-gear-wide-connected', 1, 1, NULL, '2025-04-11 03:50:39', NULL, '2026-04-13 06:08:32'),
	(3, 2, 'Profile', 'bi bi-buildings', 3, 1, NULL, '2025-07-02 20:09:32', NULL, '2025-07-02 20:09:32'),
	(4, 2, 'Lampiran', 'bi bi-award', 4, 1, NULL, '2025-09-26 05:56:42', NULL, '2025-09-26 05:56:42'),
	(5, 3, 'Lampiran', 'bi bi-back', 1, 1, NULL, '2025-06-12 09:13:26', NULL, '2025-06-12 09:13:26'),
	(6, 3, 'Master', 'bi bi-book', 2, 1, NULL, '2025-06-29 21:51:01', NULL, '2025-06-29 21:51:01'),
	(7, 3, 'Laporan', 'bi bi-file-earmark-bar-graph', 3, 1, NULL, '2025-09-16 22:09:31', NULL, '2025-09-25 14:40:39'),
	(8, 4, 'Master', 'bi bi-book', 3, 1, NULL, '2026-04-13 15:44:58', NULL, '2026-04-15 03:08:38'),
	(9, 4, 'Inventory', 'bi bi-boxes', 6, 1, NULL, '2026-04-13 15:53:46', NULL, '2026-04-20 19:20:32'),
	(10, 4, 'Finance', 'bi bi-card-checklist', 4, 1, NULL, '2026-04-13 15:53:58', NULL, '2026-04-20 19:18:53'),
	(11, 4, 'Pembelian', 'bi bi-truck', 5, 1, NULL, '2026-04-13 15:54:09', NULL, '2026-04-20 19:19:08'),
	(12, 4, 'Sales', 'bi bi-grid-1x2', 7, 1, NULL, '2026-04-13 15:55:42', NULL, '2026-04-15 03:08:18'),
	(13, 4, 'Laporan', 'bi bi-newspaper', 8, 1, NULL, '2026-04-13 15:56:55', NULL, '2026-04-15 03:08:12'),
	(14, 4, 'Lampiran', 'bi bi-paperclip', 2, 1, NULL, '2026-04-13 15:58:21', NULL, '2026-04-15 03:08:51'),
	(15, 4, 'System', 'bi bi-gear', 1, 1, NULL, '2026-04-15 03:08:00', NULL, '2026-04-15 03:08:00'),
	(16, 5, 'System', 'bi bi-gear', 1, 1, NULL, '2026-04-21 23:29:26', NULL, '2026-04-28 02:20:34'),
	(17, 5, 'Master', 'bi bi-book', 3, 1, NULL, '2026-04-21 23:29:42', NULL, '2026-05-23 04:16:12'),
	(18, 5, 'Produksi', 'bi bi-buildings', 4, 1, NULL, '2026-04-21 23:29:58', NULL, '2026-06-17 08:42:31'),
	(19, 7, 'Master', 'bi bi-book', 1, 1, NULL, '2026-04-27 02:23:22', NULL, '2026-04-27 02:23:22'),
	(20, 5, 'Inventory', 'bi bi-boxes', 5, 1, NULL, '2026-04-29 01:30:55', NULL, '2026-06-11 03:13:16'),
	(21, 8, 'Pengaturan', 'bi bi-gear', 1, 1, NULL, '2026-05-02 23:25:36', NULL, '2026-05-05 01:57:41'),
	(22, 8, 'Master', 'bi bi-book', 2, 1, NULL, '2026-05-02 23:26:08', NULL, '2026-05-02 23:26:08'),
	(23, 8, 'Transaksi', 'bi bi-award', 3, 1, NULL, '2026-05-02 23:26:49', NULL, '2026-05-05 01:57:50'),
	(24, 9, 'Profile', 'bi bi-user', 1, 1, NULL, '2026-05-04 23:18:31', NULL, '2026-05-04 23:18:31'),
	(25, 9, 'Transaction', 'bi bi-card-checklist', 2, 1, NULL, '2026-05-04 23:19:05', NULL, '2026-05-04 23:19:05'),
	(26, 5, 'Laporan', 'bi bi-graph-up-arrow', 6, 1, NULL, '2026-05-22 08:57:46', NULL, '2026-05-23 04:16:25'),
	(27, 5, 'Lampiran', 'bi bi-archive', 2, 0, NULL, '2026-05-23 04:17:47', NULL, '2026-05-23 05:09:20');

-- Dumping structure for table dbs_actasys_inventro.tb_act_sfr_tab_mdl
DROP TABLE IF EXISTS `tb_act_sfr_tab_mdl`;
CREATE TABLE IF NOT EXISTS `tb_act_sfr_tab_mdl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tab_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tab_id` (`tab_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='module';

-- Dumping data for table dbs_actasys_inventro.tb_act_sfr_tab_mdl: ~139 rows (approximately)
DELETE FROM `tb_act_sfr_tab_mdl`;
INSERT INTO `tb_act_sfr_tab_mdl` (`id`, `tab_id`, `nama`, `link`, `urutan`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 5, 'Level Karyawan', 'lampiran/level_karyawan', 1, 1, NULL, '2025-05-16 16:07:42', NULL, '2025-05-16 16:07:42'),
	(2, 5, 'Group Supplier', 'lampiran/group_supplier', 2, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(3, 3, 'Kode Transaksi', 'profile/kode_transaksi', 7, 1, NULL, '2025-04-11 06:25:29', NULL, '2026-04-19 22:34:32'),
	(4, 3, 'Gudang', 'profile/gudang', 9, 1, NULL, '2025-04-11 06:25:29', NULL, '2026-04-19 22:34:22'),
	(5, 2, 'Software', 'system/software', 1, 1, NULL, '2025-07-02 20:10:39', NULL, '2025-07-05 04:02:11'),
	(6, 2, 'Tab', 'system/tab', 2, 1, NULL, '2025-04-11 06:24:35', NULL, '2025-04-11 06:24:35'),
	(7, 2, 'Module', 'system/module', 3, 1, NULL, '2025-05-19 14:55:06', NULL, '2025-05-19 14:55:06'),
	(8, 3, 'Company', 'profile/company', 1, 1, NULL, '2025-07-02 20:11:09', NULL, '2025-07-05 03:57:49'),
	(9, 3, 'Branch', 'profile/branch', 2, 1, NULL, '2025-07-04 15:21:58', NULL, '2025-07-04 15:21:58'),
	(10, 3, 'Store', 'profile/store', 3, 1, NULL, '2025-06-12 09:51:57', NULL, '2025-06-12 09:51:57'),
	(11, 3, 'Station', 'profile/station', 4, 1, NULL, '2025-04-11 06:17:09', NULL, '2025-04-11 06:17:09'),
	(12, 5, 'Group Customer', 'lampiran/group_customer', 3, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(13, 5, 'Kategori Gudang', 'lampiran/kategori_gudang', 4, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(14, 5, 'Level Akun 1', 'lampiran/akunlevel1', 5, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(15, 5, 'Level Akun 2', 'lampiran/akunlevel2', 6, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(16, 5, 'Level Akun 3', 'lampiran/akunlevel3', 7, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(17, 5, 'Satuan Barang', 'lampiran/satuan_barang', 8, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(18, 5, 'Kategori Barang', 'lampiran/kategori_barang', 9, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(19, 5, 'Grade Barang', 'lampiran/grade_barang', 10, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(20, 5, 'Availability', 'lampiran/availability_barang', 11, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(21, 5, 'Series', 'lampiran/series_barang', 12, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(22, 5, 'Brand', 'lampiran/brand_barang', 13, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(23, 5, 'Sub Brand 1', 'lampiran/sub_brand_barang_1', 14, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(24, 5, 'Sub Brand 2', 'lampiran/sub_brand_barang_2', 15, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(25, 5, 'Sub Brand 3', 'lampiran/sub_brand_barang_3', 16, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(26, 1, 'Jenis Kelamin', 'general/jenis_kelamin', 1, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(27, 1, 'Agama', 'general/agama', 2, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(28, 1, 'Golongan Darah', 'general/golongan_darah', 3, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(29, 1, 'Hubungan Keluarga', 'general/hubungan_keluarga', 4, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(30, 1, 'Status Perkawinan', 'general/status_perkawinan', 5, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(31, 1, 'Tingkat Pendidikan', 'general/tingkat_pendidikan', 6, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(32, 1, 'Tipe Pajak Karyawan', 'general/tipe_pajak_karyawan', 7, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(33, 1, 'Kewarganegaraan', 'general/kewarganegaraan', 8, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(34, 1, 'Valuta', 'general/valuta', 9, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(35, 1, 'Bank', 'general/bank', 10, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(36, 1, 'Hari', 'general/hari', 11, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(37, 1, 'Bulan', 'general/bulan', 12, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(38, 1, 'Tahun', 'general/tahun', 13, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(39, 1, 'Kelurahan', 'general/kelurahan', 14, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(40, 1, 'Kecamatan', 'general/kecamatan', 15, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(41, 1, 'Kota', 'general/kota', 16, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(42, 1, 'Provinsi', 'general/provinsi', 17, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(43, 3, 'Access', 'profile/access', 5, 1, NULL, '2025-05-19 14:55:06', NULL, '2025-05-19 14:55:06'),
	(44, 1, 'Bidang Usaha', 'general/bidang_usaha', 18, 1, NULL, '2025-05-16 16:11:23', NULL, '2025-05-16 16:11:23'),
	(45, 14, 'Level Akun', 'lampiran/akunlevel1', 1, 1, NULL, '2026-04-13 16:13:56', NULL, '2026-05-12 22:22:08'),
	(46, 14, 'Level Akun 2', 'lampiran/akunlevel2', 2, 0, NULL, '2026-04-13 16:14:21', NULL, '2026-05-12 22:21:51'),
	(47, 14, 'Level Akun 3', 'lampiran/akunlevel3', 3, 0, NULL, '2026-04-13 16:15:36', NULL, '2026-05-12 22:22:00'),
	(48, 14, 'Group Customer', 'lampiran/group_customer', 5, 1, NULL, '2026-04-13 16:16:37', NULL, '2026-04-13 16:17:14'),
	(49, 14, 'Group Supplier', 'lampiran/group_supplier', 4, 1, NULL, '2026-04-13 16:17:00', NULL, '2026-04-13 16:17:00'),
	(50, 3, 'Kode Absen', 'profile/kode_absen', 8, 1, NULL, '2026-04-14 21:51:24', NULL, '2026-04-19 22:34:13'),
	(51, 1, 'Ekspedisi', 'general/ekspedisi', 19, 1, NULL, '2026-04-17 02:55:55', NULL, '2026-04-17 02:55:55'),
	(52, 1, 'Jenis Kendaraan', 'general/jenis_kendaraan', 20, 1, NULL, '2026-04-17 02:56:20', NULL, '2026-04-17 02:56:20'),
	(53, 1, 'Jenis Sim', 'general/jenis_sim', 21, 1, NULL, '2026-04-17 02:56:46', NULL, '2026-04-17 02:56:46'),
	(54, 1, 'Satuan', 'general/satuan', 22, 1, NULL, '2026-04-17 02:57:16', NULL, '2026-04-17 02:57:16'),
	(55, 3, 'User', 'profile/user', 6, 1, NULL, '2026-04-19 22:34:03', NULL, '2026-04-19 22:34:03'),
	(56, 15, 'Setup', 'system/kode_transaksi', 2, 1, NULL, '2026-04-20 08:38:54', NULL, '2026-05-16 20:03:16'),
	(57, 15, 'Default', 'system/default', 3, 0, NULL, '2026-04-20 08:39:19', NULL, '2026-05-16 20:03:20'),
	(58, 14, 'Level Customer', 'lampiran/level_customer', 6, 0, NULL, '2026-04-20 08:42:34', NULL, '2026-05-12 22:24:17'),
	(59, 14, 'Kategori Barang', 'lampiran/kategori_barang', 7, 1, NULL, '2026-04-20 08:44:07', NULL, '2026-04-20 08:44:07'),
	(60, 14, 'Grade Barang', 'lampiran/grade_barang', 8, 0, NULL, '2026-04-20 08:44:31', NULL, '2026-05-12 23:05:41'),
	(61, 14, 'Availability', 'lampiran/availability_barang', 9, 0, NULL, '2026-04-20 08:44:58', NULL, '2026-05-12 23:05:54'),
	(62, 14, 'Series Barang', 'lampiran/series_barang', 10, 0, NULL, '2026-04-20 08:45:24', NULL, '2026-05-12 23:06:09'),
	(63, 14, 'Brand Barang', 'lampiran/brand_barang', 11, 0, NULL, '2026-04-20 08:46:31', NULL, '2026-05-12 23:06:17'),
	(64, 14, 'Sub Brand Barang 1', 'lampiran/sub_brand_barang_1', 12, 0, NULL, '2026-04-20 08:47:03', NULL, '2026-05-12 23:06:49'),
	(65, 14, 'Sub Brand Barang 2', 'lampiran/sub_brand_barang_2', 13, 0, NULL, '2026-04-20 08:48:04', NULL, '2026-05-12 23:06:57'),
	(66, 14, 'Sub Brand Barang 3', 'lampiran/sub_brand_barang_3', 14, 0, NULL, '2026-04-20 08:49:13', NULL, '2026-05-12 23:07:06'),
	(67, 8, 'Karyawan', 'master/karyawan', 1, 1, NULL, '2026-04-20 08:49:46', NULL, '2026-04-20 08:49:46'),
	(68, 8, 'Akun', 'master/akun', 2, 1, NULL, '2026-04-20 08:49:58', NULL, '2026-04-20 08:49:58'),
	(69, 8, 'Supplier', 'master/supplier', 3, 1, NULL, '2026-04-20 08:50:40', NULL, '2026-04-20 08:50:40'),
	(70, 8, 'Customer', 'master/customer', 4, 1, NULL, '2026-04-20 08:51:24', NULL, '2026-04-20 08:51:24'),
	(71, 8, 'Barang', 'master/barang', 5, 1, NULL, '2026-04-20 08:51:48', NULL, '2026-04-20 08:51:48'),
	(72, 9, 'Opening Balance', 'inventory/opening_balance', 1, 1, NULL, '2026-04-20 08:55:08', NULL, '2026-04-20 08:55:08'),
	(73, 9, 'Stok Opname', 'inventory/stok_opname', 2, 1, NULL, '2026-04-20 08:55:54', NULL, '2026-04-20 08:55:54'),
	(74, 10, 'Pemasukan Dana', 'finance/pemasukan_dana', 1, 1, NULL, '2026-04-20 08:56:17', NULL, '2026-05-17 00:13:06'),
	(75, 9, 'Transfer Stok', 'inventory/transfer_stok_kirim', 3, 1, NULL, '2026-04-20 08:57:05', NULL, '2026-05-16 21:59:41'),
	(76, 9, 'Transfer Stok Terima', 'inventory/transfer_stok_terima', 5, 0, NULL, '2026-04-20 08:57:41', NULL, '2026-05-16 21:59:47'),
	(77, 9, 'Overzak Stok', 'inventory/overzak_stok', 4, 1, NULL, '2026-04-20 09:06:49', NULL, '2026-05-16 21:59:51'),
	(78, 9, 'Waste', 'inventory/waste', 5, 1, NULL, '2026-04-20 09:07:12', NULL, '2026-05-16 21:59:56'),
	(79, 10, 'Pengeluaran Dana', 'finance/pengeluaran_dana', 2, 1, NULL, '2026-04-20 09:08:48', NULL, '2026-05-17 00:20:58'),
	(80, 10, 'Koreksi Journal', 'finance/koreksi_journal', 3, 1, NULL, '2026-04-20 09:09:11', NULL, '2026-05-17 00:21:07'),
	(81, 11, 'Pesanan Pembelian', 'pembelian/pesanan', 1, 1, NULL, '2026-04-20 09:10:24', NULL, '2026-04-20 09:10:24'),
	(82, 11, 'Penerimaan Pembelian', 'pembelian/penerimaan_pembelian', 2, 1, NULL, '2026-04-20 09:10:39', NULL, '2026-04-20 09:11:13'),
	(83, 11, 'Retur Pembelian', 'pembelian/retur_pembelian', 3, 1, NULL, '2026-04-20 09:10:58', NULL, '2026-04-20 09:10:58'),
	(84, 12, 'POS', 'sales/pos', 1, 1, NULL, '2026-04-20 18:55:08', NULL, '2026-04-20 18:55:08'),
	(85, 13, 'Laporan Persediaan', 'laporan/laporan_persediaan', 1, 1, NULL, '2026-04-20 18:55:50', NULL, '2026-04-20 18:55:50'),
	(86, 13, 'Laporan Penjualan', 'laporan/laporan_penjualan', 2, 1, NULL, '2026-04-20 18:56:12', NULL, '2026-04-20 18:56:12'),
	(87, 16, 'Setting', 'system/kode_absen', 4, 0, NULL, '2026-04-21 23:31:32', NULL, '2026-06-17 01:43:18'),
	(88, 17, 'Lampiran', 'master/satuan', 1, 1, NULL, '2026-04-21 23:31:55', NULL, '2026-06-17 02:27:13'),
	(89, 26, 'Laporan Pembelian', 'laporan/pembelian', 3, 1, NULL, '2026-04-21 23:32:10', NULL, '2026-06-13 02:26:35'),
	(90, 17, 'Produk', 'master/produk', 3, 1, NULL, '2026-04-21 23:32:31', NULL, '2026-06-11 03:21:24'),
	(91, 18, 'Penjualan', 'transaksi/penjualan', 2, 1, NULL, '2026-04-21 23:33:44', NULL, '2026-06-13 01:44:21'),
	(92, 26, 'Neraca', 'laporan/neraca', 5, 0, NULL, '2026-04-22 03:07:34', NULL, '2026-06-13 02:04:52'),
	(93, 17, 'Mesin', 'master/mesin', 5, 1, NULL, '2026-04-22 03:07:54', NULL, '2026-06-11 03:24:02'),
	(94, 17, 'Customer', 'master/customer', 2, 1, NULL, '2026-04-22 03:17:26', NULL, '2026-06-17 02:46:19'),
	(95, 20, 'Overzak Stok', 'master/overzak_stok', 5, 1, NULL, '2026-04-22 03:17:43', NULL, '2026-06-11 03:41:45'),
	(96, 20, 'Waste', 'transaksi/waste', 4, 1, NULL, '2026-04-22 03:18:03', NULL, '2026-06-11 03:41:35'),
	(97, 18, 'Pembelian', 'transaksi/pembelian', 1, 1, NULL, '2026-04-22 03:21:50', NULL, '2026-06-13 01:40:25'),
	(98, 14, 'Department', 'lampiran/department', 15, 0, NULL, '2026-04-24 01:27:26', NULL, '2026-05-13 01:22:57'),
	(99, 14, 'Divisi Karyawan', 'lampiran/divisi', 16, 1, NULL, '2026-04-24 01:27:43', NULL, '2026-05-13 01:23:35'),
	(100, 14, 'Shift', 'lampiran/shift', 16, 0, NULL, '2026-04-24 01:27:59', NULL, '2026-05-13 01:23:02'),
	(101, 19, 'Vendor', 'master/vendor', 1, 1, NULL, '2026-04-27 02:24:09', NULL, '2026-04-27 02:40:12'),
	(102, 19, 'Host', 'master/host', 2, 1, NULL, '2026-04-27 02:40:26', NULL, '2026-04-27 02:40:26'),
	(103, 19, 'Sales', 'master/sales', 3, 1, NULL, '2026-04-27 02:41:50', NULL, '2026-04-27 02:41:50'),
	(104, 19, 'Client', 'master/client', 4, 1, NULL, '2026-04-27 02:42:05', NULL, '2026-04-27 02:42:05'),
	(105, 19, 'Barang', 'master/barang', 5, 1, NULL, '2026-04-27 02:42:19', NULL, '2026-04-27 02:42:19'),
	(106, 18, 'POS', 'transaksi/pos', 3, 1, NULL, '2026-04-28 23:03:14', NULL, '2026-06-13 01:44:31'),
	(107, 20, 'Opening Balance', 'inventory/opening_balance', 1, 1, NULL, '2026-04-29 01:35:40', NULL, '2026-06-11 03:29:39'),
	(108, 20, 'Stok Opname', 'inventory/stok_opname', 2, 1, NULL, '2026-04-29 01:47:34', NULL, '2026-06-11 03:30:49'),
	(109, 21, 'Edit Profile', 'setting/profile', 1, 1, NULL, '2026-05-02 23:28:14', NULL, '2026-05-05 01:44:30'),
	(110, 22, 'Produk', 'master/produk', 1, 1, NULL, '2026-05-02 23:28:38', NULL, '2026-05-05 01:45:22'),
	(111, 23, 'Order', 'trans/order', 1, 1, NULL, '2026-05-02 23:29:06', NULL, '2026-05-05 01:46:07'),
	(112, 23, 'Cancel', 'trans/cancel', 2, 1, NULL, '2026-05-02 23:29:56', NULL, '2026-05-05 01:46:15'),
	(113, 23, 'Confirm', 'trans/confirm', 3, 1, NULL, '2026-05-02 23:30:24', NULL, '2026-05-05 01:46:22'),
	(114, 24, 'Edit Profile', 'profile/edit_profile', 1, 1, NULL, '2026-05-04 23:21:47', NULL, '2026-05-04 23:21:47'),
	(115, 25, 'Pesan Vaksin', 'trans/order', 1, 1, NULL, '2026-05-04 23:23:57', NULL, '2026-05-04 23:23:57'),
	(116, 25, 'Reschedule', 'trans/reschedule', 2, 1, NULL, '2026-05-04 23:27:57', NULL, '2026-05-04 23:27:57'),
	(117, 25, 'Pembatalan', 'trans/pembatalan', 3, 1, NULL, '2026-05-04 23:28:26', NULL, '2026-05-04 23:28:26'),
	(118, 22, 'Klinik', 'master/klinik', 2, 1, NULL, '2026-05-05 01:45:52', NULL, '2026-05-05 01:45:52'),
	(119, 22, 'Salesman', 'master/salesman', 3, 1, NULL, '2026-05-05 01:49:53', NULL, '2026-05-05 01:49:53'),
	(120, 22, 'Client', 'master/client', 4, 1, NULL, '2026-05-06 07:08:02', NULL, '2026-05-06 07:08:02'),
	(121, 16, 'Profile', 'system/company', 2, 1, NULL, '2026-05-12 04:38:49', NULL, '2026-05-18 07:18:14'),
	(122, 16, 'Access', 'system/access', 3, 1, NULL, '2026-05-12 04:42:06', NULL, '2026-05-18 07:18:23'),
	(123, 16, 'Kode Penggajian', 'system/kode_penggajian', 5, 0, NULL, '2026-05-12 06:16:14', NULL, '2026-05-18 07:30:09'),
	(124, 20, 'Transfer Stok', 'inventory/transfer_stok', 3, 1, NULL, '2026-05-12 07:12:22', NULL, '2026-06-11 03:31:16'),
	(125, 15, 'Profile', 'system/company', 1, 1, NULL, '2026-05-13 02:46:44', NULL, '2026-05-13 04:30:37'),
	(126, 15, 'Store', 'system/store', 2, 0, NULL, '2026-05-13 02:47:03', NULL, '2026-05-13 04:30:47'),
	(127, 15, 'Station', 'system/station', 3, 0, NULL, '2026-05-13 02:47:47', NULL, '2026-05-13 04:30:53'),
	(128, 16, 'Software', 'system/software', 1, 1, NULL, '2026-05-18 07:17:58', NULL, '2026-05-18 07:17:58'),
	(129, 17, 'Shift', 'master/shift', 4, 0, NULL, '2026-05-21 22:41:33', NULL, '2026-05-24 10:13:03'),
	(130, 26, 'Mutasi Stok', 'laporan/mutasi_stok', 1, 1, NULL, '2026-05-22 08:58:10', NULL, '2026-06-13 02:02:55'),
	(131, 26, 'Kartu Stok', 'laporan/kartu_stok', 2, 1, NULL, '2026-05-22 08:58:40', NULL, '2026-06-13 02:04:24'),
	(132, 26, 'Laporan Penjualan', 'laporan/penjualan', 4, 1, NULL, '2026-05-22 09:03:20', NULL, '2026-06-13 02:26:30'),
	(133, 27, 'Pendidikan', 'lampiran/pendidikan', 1, 1, NULL, '2026-05-23 04:19:29', NULL, '2026-05-23 04:19:29'),
	(134, 27, 'Agama', 'lampiran/agama', 2, 1, NULL, '2026-05-23 04:20:24', NULL, '2026-05-23 04:20:24'),
	(135, 27, 'Status Perkawinan', 'lampiran/status_perkawinan', 3, 1, NULL, '2026-05-23 04:20:40', NULL, '2026-05-23 04:20:40'),
	(136, 27, 'Golongan Darah', 'lampiran/golongan_darah', 4, 1, NULL, '2026-05-23 04:20:54', NULL, '2026-05-23 04:20:54'),
	(137, 27, 'Jenis Kelamin', 'lampiran/jenis_kelamin', 5, 1, NULL, '2026-05-23 04:21:39', NULL, '2026-05-23 04:21:39'),
	(138, 27, 'Status Pajak', 'lampiran/status_pajak', 6, 1, NULL, '2026-05-23 04:22:06', NULL, '2026-05-23 04:22:06'),
	(139, 27, 'Jenis SIM', 'lampiran/jenis_sim', 7, 1, NULL, '2026-05-23 04:23:22', NULL, '2026-05-23 04:23:22');

-- Dumping structure for table dbs_actasys_inventro.tb_act_usr
DROP TABLE IF EXISTS `tb_act_usr`;
CREATE TABLE IF NOT EXISTS `tb_act_usr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `access_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `access_id` (`access_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='user';

-- Dumping data for table dbs_actasys_inventro.tb_act_usr: ~11 rows (approximately)
DELETE FROM `tb_act_usr`;
INSERT INTO `tb_act_usr` (`id`, `access_id`, `nama`, `email`, `handphone`, `password`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 1, 'eko', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:42:04'),
	(2, 1, 'euis', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:43:23'),
	(3, 1, 'eliza', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:43:13'),
	(4, 3, 'akbar', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:41:55'),
	(5, 1, 'wahyu', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:45:12'),
	(6, 1, 'ahmad', NULL, NULL, '123', 1, NULL, '2025-05-26 10:08:25', NULL, '2026-04-20 02:32:53'),
	(7, 4, 'kartika', NULL, NULL, '123', 1, NULL, '2025-05-26 11:40:39', NULL, '2026-04-20 02:43:34'),
	(8, 4, 'risat', NULL, NULL, '123', 1, NULL, '2025-05-26 11:38:32', NULL, '2026-04-20 02:44:04'),
	(9, 4, 'lucy', NULL, NULL, '123', 1, NULL, '2025-05-26 11:37:37', NULL, '2026-04-20 02:43:49'),
	(12, 11, 'admin', 'andipraseno@gmail.com', '089501555155', '123', 1, NULL, '2026-04-22 01:12:18', NULL, '2026-04-22 01:12:18'),
	(14, 17, 'admin@suritex', 'actasysserve@gmail.com', '089501555155', '123', 1, NULL, '2026-05-24 01:27:38', NULL, '2026-05-24 01:29:57');

-- Dumping structure for table dbs_actasys_inventro.tb_mst_brg
DROP TABLE IF EXISTS `tb_mst_brg`;
CREATE TABLE IF NOT EXISTS `tb_mst_brg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint(20) NOT NULL DEFAULT 0,
  `satuan_id` bigint(20) NOT NULL DEFAULT 0,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `kategori_id` (`kategori_id`),
  KEY `satuan_id` (`satuan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='barang';

-- Dumping data for table dbs_actasys_inventro.tb_mst_brg: ~0 rows (approximately)
DELETE FROM `tb_mst_brg`;

-- Dumping structure for table dbs_actasys_inventro.tb_mst_brg_kat
DROP TABLE IF EXISTS `tb_mst_brg_kat`;
CREATE TABLE IF NOT EXISTS `tb_mst_brg_kat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='barang - kategori';

-- Dumping data for table dbs_actasys_inventro.tb_mst_brg_kat: ~0 rows (approximately)
DELETE FROM `tb_mst_brg_kat`;
INSERT INTO `tb_mst_brg_kat` (`id`, `nama`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 'BAHAN BAKU', 1, NULL, '2026-06-17 02:58:03', NULL, '2026-06-17 02:58:03');

-- Dumping structure for table dbs_actasys_inventro.tb_mst_brg_sat
DROP TABLE IF EXISTS `tb_mst_brg_sat`;
CREATE TABLE IF NOT EXISTS `tb_mst_brg_sat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='barang - satuan';

-- Dumping data for table dbs_actasys_inventro.tb_mst_brg_sat: ~2 rows (approximately)
DELETE FROM `tb_mst_brg_sat`;
INSERT INTO `tb_mst_brg_sat` (`id`, `nama`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(21, 'YARD', 1, NULL, '2026-06-17 02:38:46', NULL, '2026-06-17 02:38:46'),
	(22, 'METER', 1, NULL, '2026-06-17 02:38:54', NULL, '2026-06-17 02:38:54');

-- Dumping structure for table dbs_actasys_inventro.tb_mst_cst
DROP TABLE IF EXISTS `tb_mst_cst`;
CREATE TABLE IF NOT EXISTS `tb_mst_cst` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='customer';

-- Dumping data for table dbs_actasys_inventro.tb_mst_cst: ~4 rows (approximately)
DELETE FROM `tb_mst_cst`;
INSERT INTO `tb_mst_cst` (`id`, `group_id`, `nama`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 1, 'I', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(2, 2, 'II', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(3, 3, 'III', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(4, 4, 'IV', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27');

-- Dumping structure for table dbs_actasys_inventro.tb_mst_cst_grp
DROP TABLE IF EXISTS `tb_mst_cst_grp`;
CREATE TABLE IF NOT EXISTS `tb_mst_cst_grp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='customer - group';

-- Dumping data for table dbs_actasys_inventro.tb_mst_cst_grp: ~0 rows (approximately)
DELETE FROM `tb_mst_cst_grp`;

-- Dumping structure for table dbs_actasys_inventro.tb_mst_msn
DROP TABLE IF EXISTS `tb_mst_msn`;
CREATE TABLE IF NOT EXISTS `tb_mst_msn` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `group_id` (`kategori_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='mesin';

-- Dumping data for table dbs_actasys_inventro.tb_mst_msn: ~4 rows (approximately)
DELETE FROM `tb_mst_msn`;
INSERT INTO `tb_mst_msn` (`id`, `kategori_id`, `nama`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 1, 'I', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(2, 2, 'II', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(3, 3, 'III', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27'),
	(4, 4, 'IV', 1, NULL, '2024-08-19 01:47:27', NULL, '2024-08-19 01:47:27');

-- Dumping structure for table dbs_actasys_inventro.tb_mst_msn_kat
DROP TABLE IF EXISTS `tb_mst_msn_kat`;
CREATE TABLE IF NOT EXISTS `tb_mst_msn_kat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='kategori - mesin';

-- Dumping data for table dbs_actasys_inventro.tb_mst_msn_kat: ~0 rows (approximately)
DELETE FROM `tb_mst_msn_kat`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
